<?php
App::uses('AppController', 'Controller');
/**
 * ContentPromotions Controller
 *
 * @property ContentPromotion $ContentPromotion
 * @property PaginatorComponent $Paginator
 */
class ContentPromotionsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	var $helpers = array('Html','Js' => array('Jquery'));//,'Javascript');
    var $components = array( 'Paginator', 'RequestHandler', 'UserData' );
	var $uses = array ('ContentPromotion', 'Content', 'OpenCampaign', 'Company', 'Blogger', 'Queue', 'OcCondition', 'Condition');
/**
 * index method
 *
 * @return void
 */
	public function index() {
		// disabled method
		return null;
		
		$this->ContentPromotion->recursive = 0;
		$this->set('ocResponses', $this->Paginator->paginate());
	}
	

	public function has_access_for_cpr_eval($cpr_id)
	{
		$result = array ('msg'=>"", 'success' => 0);
		
		// check publisher id is same
		$have_access = false;
		if ($this->UserData->isAdmin())
		{
			$have_access = true;
		}
		else
		{
			$blogger_id = $this->UserData->getBloggerId($publisher_id);
			$have_access = $this->ContentPromotion->doesBloggerOwnCpr($blogger_id, $cpr_id);
		}

		if (!$have_access)
		{
			$result['msg'] = "Looks like you are not logged in; ensure you have proper permissions.";
			$result['success'] = 0;
			$this->set('result', $result);
			return $result;
		}
		else
		{
			$result['success'] = 1;
		}
		
		return $result;
	}

#### imgur eval starts here ####
	public function ajax_evaluate_imgur_response()
	{
		if (!($this->RequestHandler->isAjax()))
		{
			return;
		}
		
		$result = array ('msg'=>"", 'success' => 0);
		
		$data = $this->request->data;
		$resp_data = $data['resp_data'];
		$cpr_id = $resp_data['cpr_id'];
		
		$result = $this->has_access_for_cpr_eval($cpr_id);
		if ($result['success']==1)
		{
			$result = $this->evaluate_imgur_response($cpr_id);
		}
		
		return $result;
		
	}
	
	public function ajax_evaluate_and_accept_imgur_response()
	{
		$result = $this->ajax_evaluate_imgur_response();
		if ($result['success'] == 1)
		{
			$best_condition = $result['best_condition'];
			$acceptance_result = $this->accept_response($cpr_id, $best_condition);
			$result = $acceptance_result;
		}
		
		$this->set('result', $result);
		
		return $result;
	}
	
	public function evaluate_and_accept_imgur_response($cpr_id)
	{
		$result = $this->evaluate_imgur_response($cpr_id);
		
		if ($result['success'] == 1)
		{
			$best_condition = $result['best_condition'];
			$acceptance_result = $this->accept_response($cpr_id, $best_condition);
			$result = $acceptance_result;
		}
		
		$this->set('result', $result);
		
		return $result;
	}
	
	public function evaluate_imgur_response($cpr_id)
	{
		$result = array ('msg'=>"", 'success' => 0);
				
		// ensure given post_id is really part of given cpr_id
		$cpr = $this->ContentPromotion->getRawResponseByCprId($cpr_id);
		$content_id = $cpr['ContentPromotion']['content_id'];
		$imgur_link = $cpr['ContentPromotion']['response_blog_link'];
		
		if ($this->ContentPromotion->getType($cpr) != 'imgur')
		{
			$result['msg'] = "Not a imgur promotion..";
			$result['success'] = 0;
			$this->set('result', $result);
			return $result;
		}
		
		$valid_points = 0;
		$command = PYTHON_SCRIPTS_FOLDER."test_imgur_parser.py {$imgur_link}";
		$output = array();
		//debug ($command);
		exec($command, $output);
		//$output = array("{\"points\": 2062, \"comments\": 211, \"views\": 162929}"	);
		//debug($output);
		if (!empty($output[0]) && $output[0] != 'Exception')
		{
			$output = json_decode($output[0]);
			//debug($output);
			if (!empty($output->points))
			{
				$valid_points = $output->points;
			}
		}
		
		$occons = $this->OcCondition->getRawConditionsFromOCId($content_id);
		
		$best_condition = null;
		$dollar_gained = 0;
			
		foreach($occons as $index=>$occon)
		{
			$con_id = $occon['OcCondition']['condition_id'];
			$min_needed = $occon['OcCondition']['param1'];
			$offer_worth = $occon['OcCondition']['offer_worth'];
			$meets = false;
			$msg = "";
			
			#debug($con_id);
			if ($con_id == 10) // Minimum imgur points
			{
				if ($valid_points >= $min_needed)
				{
					$meets = true;
					$best_condition = $con_id;
				}
				else
				{
					$msg = strval($min_needed - $valid_like) . " short of target.";
					#debug(strval($min_needed - $valid_like));
				}
			}
			
			if ($meets)
			{
				$result['success'] = 1;
				$result['conditions_met'][] = $con_id;
				if ($dollar_gained < $offer_worth)
				{
					$result['best_condition'] = $con_id;
					$dollar_gained = $offer_worth;
				}
			}
			else
			{
				$results['unmet_conditions'][] = array('con_id' => $con_id, 'msg' => $msg);
			}
		}
		
		
		$this->set('result', $result);
		
		return $result;
	}

#### imgur eval ends here ####

#### reddit eval starts here ####	
	public function share_on_reddit()
	{
		
		$result = 0;
		
		$this->layout = 'ajax';
		if ($this->RequestHandler->isAjax())
		{
			$data = $this->request->data;
			$content_id = $data['content_id'];
			$title = $data['title'];
			$link = $data['link'];
			if (empty($data['subreddit']))
			{
				$subredditname = 'NEWS'; // this we should infer from company category;
			}
			else
			{
				$subredditname = $data['subreddit'];
			}
			$linktype = 'link';
			
			
			App::import('Vendor', 'RedditApiClient/HttpRequest');
			App::import('Vendor', 'RedditApiClient/HttpResponse');
			App::import('Vendor', 'RedditApiClient/Reddit');
			App::import('Vendor', 'RedditApiClient/RedditException');
			$reddit = new RedditApiClient\Reddit;
			
			$reddit->setModHashAndSessionFromUDCVals($this->UserData);
			$response_link = $reddit->submit($subredditname, $linktype, $title, $link);
			$reddit->updateModHashAndSessionInUDC($this->UserData);
			
			//debug($response_link);
			
			if (!empty($response_link))
			{
				$this->request->data['redditpost_commentpage_link'] = $response_link;
				$updated_response = $this->update_reddit_response();
				//debug($updated_response);
				if ($updated_response)
				{
					$result = 1;
				}
			}
			else
			{
				$result = 0;
			}
			
		}
		
		$this->set('result', $result);
        return $result;
	
	}
	
	public function ajax_evaluate_reddit_response()
	{
		if (!($this->RequestHandler->isAjax()))
		{
			return;
		}
		
		$result = array ('msg'=>"", 'success' => 0);
		
		$data = $this->request->data;
		$resp_data = $data['resp_data'];
		$cpr_id = $resp_data['cpr_id'];
		
		$result = $this->has_access_for_cpr_eval($cpr_id);
		if ($result['success']==1)
		{
			$result = $this->evaluate_reddit_response($cpr_id);
		}
		
		return $result;
		
	}
	
	public function ajax_evaluate_and_accept_reddit_response()
	{
		$result = $this->ajax_evaluate_reddit_response();
		if ($result['success'] == 1)
		{
			$best_condition = $result['best_condition'];
			$acceptance_result = $this->accept_response($cpr_id, $best_condition);
			$result = $acceptance_result;
		}
		
		$this->set('result', $result);
		
		return $result;
	}
	
	public function evaluate_and_accept_reddit_response($cpr_id)
	{
		$result = $this->evaluate_reddit_response($cpr_id);
		
		if ($result['success'] == 1)
		{
			$best_condition = $result['best_condition'];
			$acceptance_result = $this->accept_response($cpr_id, $best_condition);
			$result = $acceptance_result;
		}
		
		$this->set('result', $result);
		
		return $result;
	}
	
	public function evaluate_reddit_response($cpr_id)
	{
		$result = array ('msg'=>"", 'success' => 0);
				
		// ensure given post_id is really part of given cpr_id
		$cpr = $this->ContentPromotion->getRawResponseByCprId($cpr_id);
		$content_id = $cpr['ContentPromotion']['content_id'];
		$reddit_link = $cpr['ContentPromotion']['response_blog_link'];
		
		if ($this->ContentPromotion->getType($cpr) != 'reddit')
		{
			$result['msg'] = "Not a reddit promotion..";
			$result['success'] = 0;
			$this->set('result', $result);
			return $result;
		}
		
		$valid_points = 0;
		$command = PYTHON_SCRIPTS_FOLDER."test_reddit_parser.py {$reddit_link}";
		$output = array();
		//debug ($command);
		exec($command, $output);
		//$output = array("{\"submitter\": \"funny_whiplash\", \"points\": 2541}"	);
		//debug($output);
		if (!empty($output[0]) && $output[0] != 'Exception')
		{
			$output = json_decode($output[0]);
			//debug($output);
			if (!empty($output->points))
			{
				$valid_points = $output->points;
			}
		}
		
		$occons = $this->OcCondition->getRawConditionsFromOCId($content_id);
		
		$best_condition = null;
		$dollar_gained = 0;
			
		foreach($occons as $index=>$occon)
		{
			$con_id = $occon['OcCondition']['condition_id'];
			$min_needed = $occon['OcCondition']['param1'];
			$offer_worth = $occon['OcCondition']['offer_worth'];
			$meets = false;
			$msg = "";
			
			#debug($con_id);
			if ($con_id == 9) // Minimum Reddit points
			{
				if ($valid_points >= $min_needed)
				{
					$meets = true;
					$best_condition = $con_id;
				}
				else
				{
					$msg = strval($min_needed - $valid_like) . " short of target.";
					#debug(strval($min_needed - $valid_like));
				}
			}
			
			if ($meets)
			{
				$result['success'] = 1;
				$result['conditions_met'][] = $con_id;
				if ($dollar_gained < $offer_worth)
				{
					$result['best_condition'] = $con_id;
					$dollar_gained = $offer_worth;
				}
			}
			else
			{
				$results['unmet_conditions'][] = array('con_id' => $con_id, 'msg' => $msg);
			}
		}
		
		
		$this->set('result', $result);
		
		return $result;
	}

#### reddit eval ends here ####
	
	public function evaluate_response($cpr_id, $data)
	{
		$result = array();
		$result['msg'] = "The promotion was not accepted.";
		$result['success'] = 0;
		
		$cpr = $this->ContentPromotion->getRawResponseByCprId($cpr_id);
		$response_type = $cpr['ContentPromotion']['response_type'];
		if ($response_type == 'fp_post')
		{
			$result = $this->evaluate_fb_response($cpr_id, $data);
		}
		else if ($response_type == 'fp_post')
		{
			$result = $this->evaluate_reddit_response($cpr_id, $data);
		}
		else if ($response_type == 'imgur')
		{
			$result = $this->evaluate_imgur_response($cpr_id, $data);
		}
	}
	
	public function evalutate_fb_response($data)
	{
		$result = array (
					'msg'=>"", 
					'success' => 0, 
					'conditions_met'=>array(),
					'unmet_conditions' => array()
				);
		
		$cpr_id = $data['cpr_id'];
		$post_id = $data['post_id'];
		
		$publisher_id = 0;
		$skip_from_user_id = 0;
		$is_published = 0;
		$like_count = 0;
		$comment_count = 0;
		$share_count = 0;
		$stream_info = null;
		$like_info = null;
		$comment_info = null;
		$share_info = null;
		
		$passing_comment_count = 0; // comments that pass our filtering criteria
		$non_publisher_unique_comments = 0;
		$non_publisher_comments = 0;
		$countable_likes_on_comments = 0;
		$countable_comments_on_comments = 0;
		
		$unique_likes_on_promotion = 0;
		$passing_like_count = 0;
		$unique_like_count = 0;
		
		$comments = array();
		
		if (!empty($data['stream_info']))
		{
			$stream_info = $data['stream_info'];
			// get the count info
			if (!empty($stream_info['like_info']['like_count']))
			{
				$like_count = $stream_info['like_info']['like_count'];
			}
			
			if (!empty($stream_info['comment_info']['comment_count']))
			{
				$comment_count = $stream_info['comment_info']['comment_count'];
			}
			
			if (!empty($stream_info['share_info']['share_count']))
			{
				$share_count = $stream_info['share_info']['share_count'];
			}
			$publisher_id = $stream_info['source_id'];
			$skip_from_user_id = $publisher_id;
			$is_published = $stream_info['is_published'];
			
			// check publisher id is same
			if ( !($this->UserData->isAdmin()))
			{
				$blogger_id = $this->UserData->getBloggerId($publisher_id);
			}
			
			// ensure that the object is published 
			if (!$is_published)
			{
				$result['msg'] = "The post is not published.";
				$result['success'] = 0;
				return $result;
			}
			
		}
		
		if (!empty($data['like_info']))
		{
			$like_info = $data['like_info'];
			if (!empty($like_info))
			{
				$i = 0;
				$len = count($like_info);
				while ($i < $len)
				{
					$this_like = $like_info[$i];
					$user_id = $this_like['user_id'];
					if ($user_id != $publisher_id)
					{
						$unique_likes_on_promotion = $unique_likes_on_promotion + 1;
					}
					$i = $i + 1;
				}
			}
		}
		
		if (!empty($data['comment_info']))
		{
			$comment_info = $data['comment_info'];
			
			if (!empty($comment_info))
			{
				$comments_from_ary = [];
				$comment_texts = [];
				
				$i = 0;
				$len = count($comment_info);
				while ($i < $len)
				{
					$this_comment = $comment_info[$i];
					
					$is_private = $this_comment['is_private'];
					// if comment is private, we dont consider that
					//
					if (!$is_private)
					{
						$comment_texts[] = $this_comment['text'];
						
						$likes_on_it = $this_comment['likes'];
						$countable_likes_on_comments += $likes_on_it-1; // we consider 1 less from likes on comments
															// this prevents the user's like until we use fb php sdk
						$comments_on_it = $this_comment['comment_count'];
						$countable_comments_on_comments = 0;	// at present, until we use fb php sdk, we dont consider comments on comment
						
						// check who is commenter
						$comment_from_id = $this_comment['fromid'];
						// if commenter is no publisher then --
						if ($comment_from_id != $publisher_id)
						{
							$non_publisher_comments++;
							if (!(array_key_exists($comments_from_id, $comments_from_ary)))
							{
								$non_publisher_unique_comments++;
								$comments_from_ary[$comments_from_id] = 1;
							}
						}
						
					}
					$i = $i + 1;
				}
			}
		}
		
		if (!empty($data['share_info']))
		{
			$share_info = $data['share_info'];
		}
		
		// Get Conditions
		$cpr = $this->ContentPromotion->getRawResponseByCprId($cpr_id);
		if (empty($cpr['ContentPromotion']['content_id']))
		{
			$result['msg'] = "No campaign associated with the response. Ensure you are forwarding right data.";
			$result['success'] = 0;
			return $result;
		}
		else
		{
			$content_id = $cpr['ContentPromotion']['content_id'];
		}
		
		// now we have following data calculated 
		/*
				$passing_comment_count = 0; // comments that pass our filtering criteria
				$non_publisher_unique_comments = 0;
				$non_publisher_comments = 0;
				$countable_likes_on_comments = 0;
				$countable_comments_on_comments = 0;
				
				$unique_likes_on_promotion = 0;
				$passing_like_count = 0;
				$unique_like_count = 0;
		*/
		$occons = $this->OcCondition->getRawConditionsFromOCId($content_id);
		
		$dollar_gained = 0;			
		foreach($occons as $index=>$occon)
		{
			$con_id = $occon['Condition']['condition_id'];
			$min_needed = $occon['OcCondition']['param1'];
			$offer_worth = $occon['OcCondition']['offer_worth'];
			$meets = false;
			$msg = "";
			
			if ($con_id == 2) // Minimum FB Likes
			{
				$valid_likes = $unique_likes_on_promotion + $countable_likes_on_comments;
				if ($valid_like >= $min_needed)
				{
					$meets = true;
				}
				else
				{
					$msg = strval($min_needed - $valid_like) . " short of target.";
				}
			}
			else if ($con_id == 3) // Minimum FB Shares
			{
				$valid_shares = $share_count;
				if ($valid_shares >= $min_needed)
				{
					$meets = true;
				}
				else
				{
					$msg = strval($min_needed - $valid_shares) . " short of target.";
				}
			}
			else if ($con_id == 4) // Minimum FB Comments
			{
				$valid_comments = $non_publisher_comments;
				if ($valid_comments >= $min_needed)
				{
					$meets = true;
				}
				else
				{
					$msg = strval($min_needed - $valid_comments) . " short of target.";
				}
			}
			else if ($con_id == 6) // Minimum FB Likes and Comments
			{
				$valid_likes = $unique_likes_on_promotion + $countable_likes_on_comments;
				$valid_comments = $non_publisher_comments;
				$valid_likes_and_comments = $valid_likes + $valid_comments;
				if ($valid_likes_and_comments >= $min_needed)
				{
					$meets = true;
				}
				else
				{
					$msg = strval($min_needed - $valid_likes_and_comments) . " short of target.";
				}
			}
			else if ($con_id == 7) // Minimum FB Likes, Share and Comments
			{
				$valid_likes = $unique_likes_on_promotion + $countable_likes_on_comments;
				$valid_comments = $non_publisher_comments;
				$valid_shares = $share_count;
				$valid_likes_share_and_comments = $valid_likes + $valid_comments + $valid_shares;
				if ($valid_likes_share_and_comments >= $min_needed)
				{
					$meets = true;
				}
				else
				{
					$msg = strval($min_needed - $valid_likes_share_and_comments) . " short of target.";
				}
			}
			
			if ($meets)
			{
				$result['success'] = 1;
				$result['conditions_met'][] = $con_id;
				if ($dollar_gained < $offer_worth)
				{
					$result['best_condition'] = $con_id;
					$dollar_gained = $offer_worth;
				}
			}
			else
			{
				$results['unmet_conditions'][] = array('con_id' => $con_id, 'msg' => $msg);
			}
			
		}
		
		return $result;
		
	}
	
	public function ajax_evaluate_and_accept_fb_response()
	{
		$result = $this->ajax_evaluate_fb_response();
		if ($result['success'] == 1)
		{
			$best_condition = $result['best_condition'];
			// try to see if the response is accepted
			// this would check against other campaign conditions, instead of the conditions associated with
			// type of promotions.
			$data = $this->request->data;
			$resp_data = $data['resp_data'];
			$cpr_id = $resp_data['cpr_id'];
			$acceptance_result = $this->accept_response($cpr_id, $best_condition);
			
			if ( 0 == $acceptance_result['success'])
			{
				$result['success'] = 0;
				$result['msg'] = $acceptance_result['msg'];
			}
			else
			{
				$result['success'] = 1;
				$result['msg'] = $acceptance_result['msg'];
				$result['condition_data'] = $this->Condition->getConditionList();
			}
		}
		
		$this->set('result', $result);
		return $result;
	}
	
	public function ajax_evaluate_fb_response()
	{
		$result = array ('msg'=>"", 'success' => 0);

		if (!($this->RequestHandler->isAjax()))
		{
			return $result;
		}
		
		$data = $this->request->data;
		$resp_data = $data['resp_data'];
		$cpr_id = $resp_data['cpr_id'];
		$post_id = $resp_data['post_id'];
			
		
		// check publisher id is same
		$have_access = true;
		if ( !($this->UserData->isAdmin()))
		{
			$blogger_id = $this->UserData->getBloggerId($publisher_id);
			$have_access = $this->ContentPromotion->doesBloggerOwnCpr($blogger_id, $cpr_id);
		}

		if (!$have_access)
		{
			$result['msg'] = "Looks like you are not logged in; ensure you have proper permissions.";
			$result['success'] = 0;
			$this->set('result', $result);
			return $result;
		}
		
		// ensure given post_id is really part of given cpr_id
		$cpr = $this->ContentPromotion->getRawResponseByCprId($cpr_id);
		$encoded_response_data = $cpr['ContentPromotion']['response_data'];
		if (empty($encoded_response_data))
		{
			$result['msg'] = "Looks like something went wrong.";
			$result['success'] = 0;
			$this->set('result', $result);
			return $result;
		}
		$response_data = json_decode($encoded_response_data);
		if (empty($response_data))
		{
			$result['msg'] = "Looks like something went wrong.";
			$result['success'] = 0;
			$this->set('result', $result);
			return $result;
		}
		
		if (!empty($response_data['post_id']))
		{
			$post_id_in_record = $response_data['post_id'];
		}
		else
		{
			$post_id_in_record = $cpr['OcResposne']['response_blog_link'];
		}
		
		// ensure given post id belongs to given cpr id
		if ($post_id_in_record != $post_id)
		{
			$result['msg'] = "Unknown error. Ensure, you are forwarding right data.";
			$result['success'] = 0;
			$this->set('result', $result);
			return $result;
		}
		
		
		$result = $this->evalutate_fb_response($resp_data);
		
		
		$this->set('result', $result);
		
		return $result;
		
	}
	
	private function accept_response($cpr_id, $con_id)
	{
		/* This routine may have synchronization problems.*/
		
		// remove from the queue
		// increase met_so_far count in condition
		// update whether the campaign shouldn't be active anymore.
		// mark as accepted
		
		$desc_result = array ('msg'=>"", 'success' => 0);
		
		//$result = false;
		
		$cpr = $this->ContentPromotion->getRawResponseByCprId($cpr_id);
		
		if (empty($cpr))
		{
			$desc_result['msg'] = "No such campaign found.";
			$desc_result['success'] = 0;
			$this->set('result', $desc_result);
			return $desc_result;
		}
		
		if ($cpr['ContentPromotion']['processing_result'] == 1)
		{
			$desc_result['msg'] = "Promotion already accepted.";
			$desc_result['success'] = 0;
			$this->set('result', $desc_result);
			return $desc_result;
		}
		
		$content_id = $cpr['ContentPromotion']['content_id'];
		
		// Campaign's active status is only used as a final blocker from listing it to users for promotions
		// shouldn't be used for promotion acceptance
		//
		//$is_campaign_active = $this->OpenCampaign->isActive($content_id);
		
		//if ($is_campaign_active)
		//{
			$increment_result = $this->OcCondition->increment_met_so_far($content_id, $con_id);
			
			if ( $increment_result['could_increment'] )
			{
				$met_so_far = $increment_result['met_so_far'];
				$max_count = $increment_result['max_count'];
				
				/* this is a bug, campaign shud deactivate only if all conditions associated with it have been promoted for max times*/
				if ($met_so_far == $max_count) 
				{
					$this->OpenCampaign->deactivate($content_id);
				}
				
				if ($this->ContentPromotion->mark_accepted($cpr_id))
				{
					$desc_result['msg'] = "Congratulations! Promotion is accepted. :).";
					$desc_result['success'] = 1;
					
				}
				else
				{
					$desc_result['msg'] = "Unknown error in accepting promotion.";
					$desc_result['success'] = 0;
				}
				$this->Queue->clearit($cpr_id);
				
			}
			else
			{
				$desc_result['msg'] = "Maximum promotions count is already hit for this campaign.";
				$desc_result['success'] = 0;
			}
		//}
		
		$this->set('result', $desc_result);
		return $desc_result;
		
	}
	
	private function unaccept_response($cpr_id)
	{
		$update_successful = $this->ContentPromotion->mark_unaccepted($cpr_id);
		if ($update_successful)
		{
			$this->Queue->clearit($cpr_id);
		}
		return $update_successful;
	}
	
	public function campaign_promotions($content_id=null, $company_id=null) {
		
		$result = array ('success' => 0, 'msg' =>'');
		$msg = "";
		$success = 0;
		$re_cprs = array();
		
		$is_ajax = $this->RequestHandler->isAjax();
		$this->set('is_ajax', $is_ajax);
		if ($is_ajax)
		{
			$this->layout = 'ajax';
		}
		
		if (!($this->UserData->isAdmin()) && !($this->UserData->isCompany()))
		{
			$msg = "Looks like you don't have access to the promotions. Please login with correct permissions.";
			$success = 0;
		}
		
		else if (!$content_id)
		{
			$this->Session->setFlash(__('Please provide campaign\'s Id to check its details. We don\'t expect you to see this error, but if this persists, then let us know and we will resolve it immediately for better experience of all.'));
			$this->set('ocResponses', null);
			return null;
		}
		
		else
		{
		
			$content = $this->Content->getRawContentInfo($content_id);
			$this->set('content', $content);
		
			if ($this->UserData->isCompany())
			{
				$company_id = $this->UserData->getCompanyId();
				$has_access = ($content['Content']['company_id'] == $company_id);
				//$company_owns_campaign = $this->OpenCampaign->doesCompanyOwnCampaign($company_id, $oc_id);
				if (!$has_access)
				{
					$msg = "Looks like you don't have access to the promotions. Please login with correct permissions.";
					$success = 0;
				}
			}
			
			if ($has_access)
			{
				$re_cprs = $this->ContentPromotion->getRawResponsesByContentId($content_id);
				$success = 1;
			}
			
			/*
			$paginate = array(
				'conditions' => array(
					'ContentPromotion.content_id' => $content_id
				),
				'recursive' => -1
			);
			$pre_settings = $this->Paginator->settings;
			$this->Paginator->settings = $paginate;
			$cpr_paginated = $this->Paginator->paginate();
			$this->Paginator->settings = $pre_settings;
			$this->set('ocResponses', $cpr_paginated);
			*/
		}
			
		if ($is_ajax)
		{
			$result['success'] = $success;
			$result['msg'] = $msg;
			$result['ocResponses'] = $re_cprs;
			$this->set('result', $result);
			$this->set('content_id', $content_id);
		
		}
		else
		{
			$this->set('ocResponses', $re_cprs);
			$this->set('content_id', $content_id);
		
			if ("" != $msg)
			{
				$this->Session->setFlash($msg);
			}
		}
		
		return;
		
	}
	
	public function ajax_change_status_by_admin($cpr_id, $new_status, $con_id)
	{
		$this->layout = 'ajax';
		
		$is_ajax_request = $this->RequestHandler->isAjax();
		$is_admin = $this->UserData->isAdmin();
		$result = array ('msg'=>"", 'success'=>0);
		
		if (!$is_admin)
		{
			$result['msg'] = "Must be done by admin only.";
			$result['success'] = 0;
			
		}
		else if (!$is_ajax_request)
		{
			$result['msg'] = "Bad Request Format.";
			$result['success'] = 0;
		}
		else
		{
			if ($new_status == 1)
			{
				$result = $this->accept_response($cpr_id, $con_id);
			}
			else if ($new_status == 0)
			{
				$op_succ = $this->unaccept_response($cpr_id, $con_id);
				if ($op_succ)
				{
					$result['success'] = 1;
					$result['msg'] = "Promotion unaccepted.";
				}
				else
				{
					$result['success'] = 0;
					$result['msg'] = "Unknown error.";
				}
			}
		}
		
		$this->set('result', $result);
		
	}
	
	public function get_queued_cprs() {
	
		if (!($this->UserData->isAdmin()))
		{
			return null;
		}
		
		$qitems = $this->Queue->getRawQueuedItems();
		
		$cpr_ids = array();
		foreach ($qitems as $index=>$qitem)
		{
			$cpr_ids[] = $qitem['Queue']['cpr_id'];
		}
		$ocResponses = $this->ContentPromotion->getRawResponsesByCprIds($cpr_ids);
		$this->set('ocResponses', $ocResponses);
		
		$conditions = $this->Condition->getConditionList();
		$this->set('conditions', $conditions);
		
	}

	public function my_promotions($blogger_id=null) {
		
		if ($this->UserData->isAdmin())
		{
			if (!$blogger_id)
			{
				$this->Session->setFlash(__('You are seeing as admin. Please provide promoter\'s Id to see details.'));
				$this->set('ocResponses', null);
				return null;
			}
			
		}
		else
		{
			$user_id = $this->UserData->getUserId();
			$blogger_id = $this->UserData->getBloggerId();
			if (empty($user_id))
			{
				$this->Session->setFlash(__('Looks like you are not logged in. Please login as a promoter to see status of your promotional entries/responses.'));
				$this->set('ocResponses', null);
				return null;
			}
		}
		
		$paginate = array(
			'conditions' => array(
				'ContentPromotion.user_id' => $user_id
			),
			'recursive' => -1
		);
		$pre_settings = $this->Paginator->settings;
		$this->Paginator->settings = $paginate;
		$cpr_paginated = $this->Paginator->paginate();
		$this->Paginator->settings = $pre_settings;
		$this->set('ocResponses', $cpr_paginated);

		return;
		
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ContentPromotion->exists($id)) {
			throw new NotFoundException(__('Invalid oc response'));
		}
		$options = array('conditions' => array('ContentPromotion.' . $this->ContentPromotion->primaryKey => $id));
		$this->set('ocResponse', $this->ContentPromotion->find('first', $options));
	}


	public function update_imgur_response(){
		
		if ($this->RequestHandler->isAjax())
		{
			$this->layout = 'ajax';
			$blogger_id = $this->UserData->getBloggerId();
			$data = $this->request->data;
			
			if (!$blogger_id)
			{
				$this->set('msg', 'You seem to be logged out. Please login as a promoter.');
				return null;
			}
			
			if (!($data['content_id']))
			{
				$this->set('success', false);
				return null;
			}			
			
			$cpr = $this->ContentPromotion->findResponseByBloggerAndOcId($blogger_id, $data['content_id']);
			
			//debug($cpr);
			
			if (empty($cpr))
			{
				$this->ContentPromotion->create();
				$cpr = array('ContentPromotion' => array());
			}
			
			$cpr['ContentPromotion']['response_blog_link'] = $data['imgurpost_link'];
			$cpr['ContentPromotion']['content_id'] = $data['content_id'];
			$cpr['ContentPromotion']['blogger_id'] = $blogger_id;
			$cpr['ContentPromotion']['response_type'] = 'reddit';
			$cpr['ContentPromotion']['processed'] = 0;
			$cpr['ContentPromotion']['created'] = date("Y-m-d H:i:s");
			
			if ($this->ContentPromotion->save($cpr)) {
				$cpr_id = $this->ContentPromotion->id;
				$this->Queue->queueit($cpr_id);
				
				$this->set('success', true);
				//$this->Session->setFlash(__('The oc response has been saved'));
				//return $this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->set('success', false);
				
			}
			return;
		}
	
	}
	
	public function update_reddit_response(){
		
		$result = false;
		
		if ($this->RequestHandler->isAjax())
		{
			$this->layout = 'ajax';
			$blogger_id = $this->UserData->getBloggerId();
			$data = $this->request->data;
			
			if (!$blogger_id)
			{
				$this->set('msg', 'You seem to be logged out. Please login as a promoter.');
				return null;
			}
			
			if (!($data['content_id']))
			{
				$this->set('success', false);
				return null;
			}			
			
			$cpr = $this->ContentPromotion->findResponseByBloggerAndOcId($blogger_id, $data['content_id']);
			
			//debug($cpr);
			
			if (empty($cpr))
			{
				$this->ContentPromotion->create();
				$cpr = array('ContentPromotion' => array());
			}
			
			$cpr['ContentPromotion']['response_blog_link'] = $data['redditpost_commentpage_link'];
			$cpr['ContentPromotion']['content_id'] = $data['content_id'];
			$cpr['ContentPromotion']['blogger_id'] = $blogger_id;
			$cpr['ContentPromotion']['response_type'] = 'reddit';
			$cpr['ContentPromotion']['processed'] = 0;
			$cpr['ContentPromotion']['created'] = date("Y-m-d H:i:s");
			
			if ($this->ContentPromotion->save($cpr)) {
				$cpr_id = $this->ContentPromotion->id;
				$this->Queue->queueit($cpr_id);
				
				$result = true;
				//$this->Session->setFlash(__('The oc response has been saved'));
				//return $this->redirect(array('action' => 'index'));
			}
		}
		
		$this->set('success', $result);
		return $result;
	
	}	
	
	public function update_blog_response(){
		
		if ($this->RequestHandler->isAjax())
		{
			$this->layout = 'ajax';
			$blogger_id = $this->UserData->getBloggerId();
			$data = $this->request->data;
			
			if (!$blogger_id)
			{
				$this->set('msg', 'You seem to be logged out. Please login as a promoter.');
				return null;
			}
			
			if (!($data['content_id']))
			{
				$this->set('success', false);
				return null;
			}			
			
			$cpr = $this->ContentPromotion->findResponseByBloggerAndOcId($blogger_id, $data['content_id']);
			
			//debug($cpr);
			
			if (empty($cpr))
			{
				$this->ContentPromotion->create();
				$cpr = array('ContentPromotion' => array());
			}
			
			$cpr['ContentPromotion']['response_blog_link'] = $data['blogpost_link'];
			$cpr['ContentPromotion']['content_id'] = $data['content_id'];
			$cpr['ContentPromotion']['blogger_id'] = $blogger_id;
			$cpr['ContentPromotion']['response_type'] = 'blog';
			$cpr['ContentPromotion']['processed'] = 0;
			$cpr['ContentPromotion']['created'] = date("Y-m-d H:i:s");
			
			if ($this->ContentPromotion->save($cpr)) {
				$cpr_id = $this->ContentPromotion->id;
				$this->Queue->queueit($cpr_id);
				
				$this->set('success', true);
				//$this->Session->setFlash(__('The oc response has been saved'));
				//return $this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->set('success', false);
				
			}
			return;
		}
	
	}
	
	public function update_fb_post_response(){
		
		if ($this->RequestHandler->isAjax())
		{
			$this->layout = 'ajax';
			$blogger_id = $this->UserData->getBloggerId();
			$data = $this->request->data;
			
			if (!$blogger_id)
			{
				$this->set('msg', 'You seem to be logged out. Please login as a promoter.');
				return null;
			}
			
			if (!($data['content_id']))
			{
				$this->set('success', false);
				return null;
			}
			
			$cpr = $this->ContentPromotion->findResponseByBloggerAndOcId($blogger_id, $data['content_id']);
			
			if (empty($cpr))
			{
				$this->ContentPromotion->create();
				$cpr = array('ContentPromotion' => array());
			}
			
			$cpr['ContentPromotion']['response_blog_link'] = $data['fb_permalink'];
			$cpr['ContentPromotion']['response_data'] = $data['fb_response_data'];
			$cpr['ContentPromotion']['content_id'] = $data['content_id'];
			$cpr['ContentPromotion']['blogger_id'] = $blogger_id;
			$cpr['ContentPromotion']['response_type'] = 'fb_post';
			$cpr['ContentPromotion']['processed'] = 0;
			$cpr['ContentPromotion']['created'] = date("Y-m-d H:i:s");
			
			if ($this->ContentPromotion->save($cpr)) {
				$cpr_id = $this->ContentPromotion->id;
				$this->Queue->queueit($cpr_id);
				$this->set('success', true);
				//$this->Session->setFlash(__('The oc response has been saved'));
				//return $this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->set('success', false);
				
			}
			return;
		}
	
	}
	
/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ContentPromotion->create();
			if ($this->ContentPromotion->save($this->request->data)) {
				$this->Session->setFlash(__('The oc response has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The oc response could not be saved. Please, try again.'));
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ContentPromotion->exists($id)) {
			throw new NotFoundException(__('Invalid oc response'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ContentPromotion->save($this->request->data)) {
				$this->Session->setFlash(__('The oc response has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The oc response could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('ContentPromotion.' . $this->ContentPromotion->primaryKey => $id));
			$this->request->data = $this->ContentPromotion->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ContentPromotion->id = $id;
		if (!$this->ContentPromotion->exists()) {
			throw new NotFoundException(__('Invalid oc response'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ContentPromotion->delete()) {
			$this->Session->setFlash(__('Oc response deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Oc response was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->ContentPromotion->recursive = 0;
		$this->set('ocResponses', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->ContentPromotion->exists($id)) {
			throw new NotFoundException(__('Invalid oc response'));
		}
		$options = array('conditions' => array('ContentPromotion.' . $this->ContentPromotion->primaryKey => $id));
		$this->set('ocResponse', $this->ContentPromotion->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->ContentPromotion->create();
			if ($this->ContentPromotion->save($this->request->data)) {
				$this->Session->setFlash(__('The oc response has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The oc response could not be saved. Please, try again.'));
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->ContentPromotion->exists($id)) {
			throw new NotFoundException(__('Invalid oc response'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ContentPromotion->save($this->request->data)) {
				$this->Session->setFlash(__('The oc response has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The oc response could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('ContentPromotion.' . $this->ContentPromotion->primaryKey => $id));
			$this->request->data = $this->ContentPromotion->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->ContentPromotion->id = $id;
		if (!$this->ContentPromotion->exists()) {
			throw new NotFoundException(__('Invalid oc response'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ContentPromotion->delete()) {
			$this->Session->setFlash(__('Oc response deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Oc response was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
