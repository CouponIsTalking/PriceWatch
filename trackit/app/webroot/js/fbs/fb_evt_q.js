function verifyevtjoin($p){$eid=$p['eid'];scn=$p['scn'];sp=$p['sp'];ecn=$p['ecn'];ep=$p['ep'];
FB.api({method: 'fql.query',query: "select rsvp_status from event_member where eid = '"+$eid+"' and uid=me()"},
	function(fql_resp){if (!fql_resp.error && fql_resp[0].rsvp_status){
		if('attending'==fql_resp[0].rsvp_status){
		 if(scn){sc=window[scn];if(sc){sp['resp']={'eventid': $eventid};sp['resp'].eid=$otl;sc(sp);}}
		 }
		}else{if(ecn){ec=window[ecn];if(ec){ec(ep);}}}
	});
}

}