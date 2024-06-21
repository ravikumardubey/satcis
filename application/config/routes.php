<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] 	='Mystart';
$route['crefresh_val']		    ='mystart/captch_refresh/';
$route['loginSuccess']			='efiling/dashboard';
///$route['loginSuccess/admin']	='mystart/admin_dashboard/admin';
$route['close']					='mystart/kill_sess';
$route['getDistrict']			='mystart/get_districts';
$route['verifyField']			='mystart/verify_mandatory';
$route['sendVcode']				='mystart/send_OTP';
$route['verifyOtP']				='mystart/otp_verify';
$route['idproofUpd/(:any)']		='mystart/upload_idproof_doc/$1';
$route['required_docs/(:any)']	='efiling/upd_required_docs/$1';
$route['uploaded_docs_display']	='efiling/uploaded_docs_display';
$route['viewUpdList']	        ='efiling/updDoc_list';
$route['dashboard']	            ='efiling/dashboard';
$route['filedcase_list']		='efiling/filedcase_list';
$route['defective_list']       ='efiling/defective_list';
//Master 
$route['org_varified']		   ='efiling/org_varified';
$route['adv_varified']		   ='efiling/adv_varified';
$route['euser_list']	       ='masters/euser_list';
$route['org_list']	           ='masters/org_list';
$route['advocate_list']	       ='masters/advocate_list';
$route['master_dash']	       ='masters/master_dash';
$route['checkslists']	       ='masters/checkslists';
$route['addchecklist']	       ='masters/addchecklist';
$route['deletecheck']	       ='masters/deletecheck';
$route['doc_master']	       ='masters/doc_master';
$route['deletedocmaster']	   ='masters/deletedocmaster';
$route['adddocmaster']	       ='masters/adddocmaster';
$route['regu_master']	       ='masters/regu_master';
$route['addregulator']	       ='masters/addregulator';
$route['deleteregulator']	   ='masters/deleteregulator';
$route['editregulator']	       ='masters/editregulator';
$route['ma_master']	           ='masters/ma_master';
$route['deletema']	           ='masters/deletema';
$route['editma']	           ='masters/editma';
$route['addmanature']	       ='masters/addmanature';
$route['act_master']	       ='masters/act_master';
$route['actdeletema']	       ='masters/actdeletema';
$route['addact']	           ='masters/addact';
$route['actedit']	           ='masters/actedit';
$route['deleteadv']	           ='masters/deleteadv';
$route['deleteorg']	           ='masters/deleteorg';
$route['masterjudge']	       ='masters/judgelist';
$route['deletejudge/(:any)']   ='masters/deletejudge/$1';
$route['judge_view']	       ='masters/judgeview';
$route['addjudge/(:any)']	    ='masters/addjudge/$1';
$route['getjudje/(:any)']	    ='masters/getjudje/$1';




//Fresh apeal routes 
$route['draftrefiling/(:any)/(:any)'] ='steps/draftrefiling/$1/$2';
$route['checklist']	           ='steps/checklist';
$route['dfrdetail/(:any)']	   ='efiling/dfrdetail/$1';
$route['basicdetail']	       ='steps/basic_details';
$route['applicant']	           ='steps/applicant';
$route['respondent']	       ='steps/respondent';
$route['counsel']	           ='steps/counsel';
$route['ma_detail']	           ='steps/ia_detail';
$route['other_fee']	           ='steps/other_fee';
$route['document_upload']	   ='steps/document_upload';
$route['payment_mode']	       ='steps/payment_mode';
$route['final_preview']	       ='steps/final_preview';
$route['getcommedit']	       ='steps/getcommedit';
$route['editSubmitcomm']	   ='steps/editSubmitcomm';
$route['getApplant']	       ='steps/getApplant';
$route['editSubmitApplent']	   ='steps/editSubmitApplent';
$route['getRespondent']	       ='steps/getRespondent';
$route['editSubmitRespondent'] ='steps/editSubmitRespondent';
$route['uploaded_docs_delete'] ='efiling/uploaded_docs_delete';
$route['addCouncel']           ='steps/addCouncel';
$route['getauthority']			     ='mystart/getauthority';
$route['saveNextcheck']        ='efiling/saveNextcheck';
$route['orgde']			                        ='filingaction/org';
$route['addMoreAppellant']	    ='efiling/addMoreAppellant';
$route['saveNext']             ='efiling/save_next';
$route['addMoreRes']			='efiling/addMoreRes';
$route['respondentSubmit']		='efiling/respondentSubmit';
$route['getAdvDetail1']		    ='efiling/getAdvDetail1';
$route['deleteParty']			='efiling/deleteParty';
$route['getAdvDetail']		    ='efiling/getAdvDetail';
$route['recoverPwd']			='mystart/restore_password';
$route['orgshowres']		       ='efiling/orgshowres';
$route['iasubmit']			       ='efiling/iasubmit';
$route['otherFeesave']		       ='efiling/otherFeesave';
$route['payfeedetailsave']		   ='efiling/payfeedetailsave';
$route['pay_page']		           ='efiling/pay_page';
$route['postalorderfinal']         ='efiling/postalorderfinal';
$route['efilingfinalsubmit']       ='efiling/efilingfinalsubmit';
$route['fhressuccess']             ='efiling/fhressuccess';
$route['payslip/(:num)']           ='filingaction/payslip/$1';

$route['deleteAdvocate']                    ='steps/deleteAdvocate';
$route['getAdvDetailEdit']                  ='steps/getAdvDetailEdit';
$route['getAdvDetailinperson']              ='steps/getAdvDetailinperson';
$route['getAdvinpers']                      ='steps/getAdvinpers';
$route['districtselected']                  ='efiling/districtselected';
$route['getApplantName']                    ='steps/getApplantName';
$route['getApplantNameEdit']                ='steps/getApplantNameEdit';
$route['getRespondentName']                 ='steps/getRespondentName';
$route['getRespondentNameEdit']             ='steps/getRespondentNameEdit';
$route['getAdv1']                            ='steps/getAdv1';
$route['basic_detailsdraft']                ='steps/basic_detailsdraft';
//Scrutiney
$route['scrutiny_list']                     ='scrutiny/scrutiny_list';
$route['scrutinyform/(:any)']               ='scrutiny/scrutinyform/$1';
$route['scrutinyaction']                    ='scrutiny/scrutinyaction';
$route['returnscrutinyform/(:any)']         ='scrutiny/returnscrutinyform/$1';
$route['createdefect/(:any)']               ='scrutiny/createdefect/$1';
$route['defectLetter_actions']              ='scrutiny/defectLetteractions';
$route['defectletterupload']			    ='scrutiny/defectletterupload';
$route['ajax_upload_defect']			    ='scrutiny/ajaxuploadefect';
$route['compliance']			            ='scrutiny/compliance';
$route['daily_order_report']			    ='scrutiny/dailyorderreport';
$route['order_view/(:any)']	                ='efiling/view_doc/$1';
//Listing 
$route['freshlisting']                      ='listing/freshlisting';
$route['asigndate/(:any)']                  ='listing/asigndate/$1';
$route['case_allocation']                   ='listing/caseallocation';
$route['cause_list']                        ='listing/causelist';
$route['caselisting']                       ='listing/caselisting';
$route['caselistingsubmit']                 ='listing/caselistingsubmit';
$route['draftcauselist/(:any)']                    ='listing/draftcauselist/$1';
$route['create_causelist']          ='bench/create_causelist';
$route['savecauselist']          	='bench/savecauselist';
// Backlog 
//$route['back_log']                          ='backlog/backlog';
$route['back_log']                           ='backlog/backlog_filing';
$route['update_backlogfiling']	             ='backlog/update_backlogfiling';
$route['update_backlogfilingres']	         ='backlog/update_backlogfilingres';
$route['getorg']                             ='backlog/getorg';
$route['getorgPet']                          ='backlog/getorgPet';
$route['additionalbacklogPet']               ='backlog/additionalbacklogPet';
$route['deletePartyPet']                     ='backlog/deletePartyPet';
$route['getorg_respRes']                     ='backlog/getorg_respRes';
$route['update_backlogfilingresRes']         ='backlog/update_backlogfilingresRes';

$route['getAdv']                            ='backlog/getAdv';

$route['getAdvdoc']                              ='backlog/getAdvdoc';
$route['getAdvAA']                               ='backlog/getAdvAA';
$route['backlogcouncelsaveAA']                   ='backlog/backlogcouncelsaveAA';
$route['deleteAdvocateAA']                       ='backlog/deleteAdvocateAA';
//Document Filing
$route['doc_basic_detail']                       ='docfiling/doc_basic_detail';
$route['doc_partydetail']                        ='docfiling/doc_partydetail';
$route['doc_upload_doc']                         ='docfiling/doc_upload_doc';
$route['doc_checklist']                          ='docfiling/doc_checklist';
$route['doc_finalprivew']                        ='docfiling/doc_finalprivew';
$route['doc_payment']                            ='docfiling/doc_payment';
$route['saveDocbasic/(:any)']                    ='docfiling/saveDocbasic/$1';
$route['docpartysave/(:any)']                    ='docfiling/docpartysave/$1';
$route['chk_listdataDoc']                        ='docfiling/chk_listdataDoc';
$route['doc_councel']                            ='docfiling/doc_councel';
$route['required_docfil/(:any)']	             ='efiling/docfiling_required_docs/$1';
$route['doc_save_nextDoc/(:any)']                ='docfiling/doc_save_nextDoc/$1';
$route['addCouncelDoc/(:any)']                   ='docfiling/addCouncelDoc/$1';
$route['deleteAdvocatDoc']                       ='docfiling/deleteAdvocatDoc';
$route['docadvsave/(:any)']                      ='docfiling/docadvsave/$1';
$route['docfpsave/(:any)']                       ='docfiling/docfpsave/$1';
$route['docsave/(:any)']                         ='docfiling/ma_action/$1';
$route['doc_finalreceipt']                       ='docfiling/doc_finalreceipt';
$route['maprint/(:any)/(:any)/(:any)/(:any)']    ='filingaction/maprint/$1/$2/$3/$4';
$route['othdocsave']                             ='docfiling/othdocsave';
$route['doc_case_filed_case']                    ='docfiling/docfiledcase';
$route['docdetail/(:any)']                       ='docfiling/docdetail/$1';
$route['va_case_list']                           ='docfiling/va_case_list';
$route['va_detail/(:any)']                       ='docfiling/va_detail/$1';


//IA filing
$route['iabasic_detail']                 ='iafiling/iabasic_detail';
$route['ia_partydetail']                 ='iafiling/ia_partydetail';
$route['ia_detail_ia']                   ='iafiling/ia_detail_ia';
$route['ia_upload_doc']                  ='iafiling/ia_upload_doc';
$route['ia_checklist']                   ='iafiling/ia_checklist';
$route['ia_finalprivew']                 ='iafiling/ia_finalprivew';
$route['ia_payment']                     ='iafiling/ia_payment';
$route['ia_finalreceipt']                ='iafiling/ia_finalreceipt';
$route['saveIabasic']                    ='iafiling/saveIabasic';
$route['iapartysave']                    ='iafiling/iapartysave';
$route['iadetailsave']                   ='iafiling/iadetailsave';
$route['doc_save_nextIA']                ='iafiling/doc_save_nextIA';
$route['chk_listdataIA']                 ='iafiling/chk_listdataIA';
$route['iafpsave']                       ='iafiling/iafpsave';
$route['iaaction']                       ='iafiling/iaaction';
$route['iasave']                         ='iafiling/iasave';
$route['chekva']                         ='iafiling/chekva';
$route['chekva']                         ='iafiling/chekva';
$route['required_ia/(:any)']	         ='efiling/upd_required_ia/$1';
$route['iaprint/(:any)/(:any)/(:any)']	='filingaction/iaprint/$1/$2/$3';
//Certified copy
$route['certbasicdetail']                        ='certified/certbasicdetail';
$route['certpartydetail']                        ='certified/certpartydetail';
$route['certuploaddoc']                          ='certified/certuploaddoc';
$route['certpf']                                 ='certified/certpf';
$route['certpayment']                            ='certified/certpayment';
$route['certreceipt']                            ='certified/certreceipt';
$route['saveCertbasic/(:any)']                   ='certified/saveCertbasic/$1';
$route['certpartysave/(:any)']                          ='certified/certpartysave/$1';
$route['required_cert/(:any)']	                 ='efiling/upd_required_cert/$1';
$route['doc_save_nextcert/(:any)']                      ='certified/doc_save_nextcert/$1';
$route['certfpsave/(:any)']                             ='certified/certfpsave/$1';
$route['matter']                                  ='certified/matter';
$route['copycertifiedCopysave']                          ='certified/copycertifiedCopysave';
$route['pendingdefect/(:any)']			        ='Refile/pendingdefect/$1';
$route['defectshowpdf/(:any)']			        ='Commancontrollaer/defectshowpdf/$1';
$route['savematter/(:any)']                      ='certified/savematter/$1';
$route['certfinalsave/(:any)']                   ='certified/certfinalsave/$1';
$route['certifyreceipt/(:any)/(:any)']		='filingaction/certifyreceipt/$1/$2';
$route['certifyreceiptview/(:any)/(:any)']		='filingaction/certifyreceiptview/$1/$2';
$route['receipt_certify_matters/(:any)/(:any)']	 ='filingaction/receipt_certify_matters/$1/$2';
$route['cetified_list']		       ='efiling/cetified_list';


$route['orgdata']			       ='backlog/orgdata';
$route['orgdataRes']			       ='backlog/orgdataRes';
$route['districtselectedres']			       ='backlog/districtselectedres';

$route['getorg_resp']			   ='backlog/getorg_resp';

$route['pendingdefect/(:any)']			        ='scrutiny/pendingdefect/$1';

$route['translate_uri_dashes']	= FALSE;
$route['logout']	           ='mystart/logout';

$route['casestatus']			    ='report/casestatus';
$route['composition']			    ='bench/composition';
$route['savebench']					='bench/saveBench';
$route['viewbench']					='bench/viewBench';
$route['removeBench']				='bench/removeBench';
$route['listing/(:num)'] 			='bench/createListing/$1';
$route['draftcauselist/(:any)']     ='bench/draftcauselist/$1';
$route['cause_list']                ='bench/causelist';
$route['backlog']                   ='backlogold/backlog';
$route['backlogsave']               ='backlogold/backlogSave';
$route['district']                  ='efiling/district';
//$route['case_proceeding']           ='court/caseProceedings';
$route['case_proceeding/(:num)']    ='court/caseProceedings/$1';
$route['proceeding']        		='court/proceeding';
$route['proceedingAction']          ='court/proceedingAction';
$route['order']       			    ='order/orderUpload';
$route['orderaction']       		='order/orderAction';


$route['backlogbasicdetailsave']      ='backlog/backlogbasicdetailsave';
$route['backlogcouncelsave']          ='backlog/backlogcouncelsave';
$route['caseupdatestatus']            ='backlog/caseupdatestatus';
$route['dropdown_party_details']      ='backlog/dropdown_party_details';
$route['i_ia_insert']                 ='backlog/i_ia_insert';
$route['delete_edit_ia']              ='backlog/delete_edit_ia';
$route['createdfr']                   ='backlog/createdfr';
$route['createdfrcasewise']           ='backlog/createdfrcasewisecase';


$route['myprofile']	           ='efiling/myprofile';

$route['change_password']	   ='efiling/change_password';

$route['changepassword']       ='efiling/changepassword';

$route['master_objection']	        ='masters/master_objection';
$route['editobjection']	            ='masters/editobjection';
$route['deleteobjection']	        ='masters/deleteobjection';
$route['addobjection']	            = 'masters/addobjection';

$route['usrole_master']	            ='masters/usrole_master';
$route['editusrole']	            ='masters/editusrole';
$route['updaterole']	            ='masters/updaterole';


$route['ia_filed_case'] ='efiling/ia_filed_case';
$route['user_list']	                 ='user/user_list';
$route['organization_list']	         ='user/organization_list';
$route['menu_access/(:any)']	     ='user/menu_access/$1';
$route['update_menu/(:any)']	     ='user/update_menu/$1';
$route['adduser/(:any)']	         ='user/adduser/$1';
$route['deleteUser']	             ='user/deleteUser';
$route['userupdate']	             ='user/userupdate';
$route['unlockuser/(:any)']	             ='user/unlockuser/$1';
$route['chnagepassword']	             ='user/chnagepassword';



$route['causelist_upload']                   ='listing/causelistupload';
$route['uploadcauselistdoc/(:any)']          ='listing/uploadcauselistdoc/$1';
$route['uploadcauselistdocsaction']          ='listing/uploadcauselistdocsaction';
$route['ajaxuploadcauselist']                ='listing/ajaxuploadcauselist';
$route['removecauselist']                    ='listing/removecauselist';

$route['editprofile']	       ='user/editprofile';



//RPEPCP files routes
$route['review_petition_filing']         ='filingaction/review_petition_filing';
$route['petitiondetail']            ='petition/petitiondetail';
$route['petitionPriority']         ='petition/petitionPriority';

$route['petitionparty']          ='petition/petitionparty';
$route['petitionadv']            ='petition/petitionadv';
$route['petitionIa']             ='petition/petitionIa';
$route['petitionDoc']            ='petition/petitionDoc';
$route['petitionCheck']          ='petition/petitionCheck';
$route['petitionCfee']           ='petition/petitionCfee';
$route['petitionFP']             ='petition/petitionFP';
$route['petitionPay']            ='petition/petitionPay';
$route['petitionReceipt']        ='petition/petitionReceipt';
$route['saveNextRPEPCbasic']                 ='petition/saveNextRPEPCbasic';
$route['petitionpartySubmit']                ='petition/petitionpartySubmit';
$route['petitionpartyPrioritySubmit']        ='petition/petitionpartyPrioritySubmit';
$route['RPEPCPaddCouncel']                   ='petition/RPEPCPaddCouncel';
$route['deleteAdvocateEPRPCP']               ='petition/deleteAdvocateEPRPCP';
$route['orgshowresrpepcp']                   ='petition/orgshowresrpepcp';
$route['rpepcpIAsubmit']                     ='petition/rpepcpIAsubmit';
$route['doc_save_nextrpepcp']                ='petition/doc_save_nextrpepcp';
$route['chk_listdatarpepcp']                 ='petition/chk_listdatarpepcp';
$route['otherFeesaveRPEPCP']                 ='petition/otherFeesaveRPEPCP';
$route['rpepcpefiling/(:any)/(:any)']        ='petition/rpepcpefiling/$1/$2';
$route['fpsave']                             ='petition/fpsave';
$route['rpcpeppayment']                      ='petition/rpcpeppayment';
$route['rpepcpsave']                         ='petition/rpepcpsave';
$route['required_rpepcp/(:any)']	         ='efiling/upd_required_rpepcp/$1';
$route['dfrdetailrpepcp/(:any)']             ='petition/dfrdetailrpepcp/$1';
$route['getAdvrpepcp']                ='petition/getAdvrpepcp';
$route['rpepcp_pay_slip/(:num)/(:any)/(:any)']='filingaction/rpepcp_pay_slip/$1/$2/$3';
$route['defect_view/(:any)']             ='efiling/defect_view/$1';
$route['causelist_view/(:any)']             ='efiling/causelist_view/$1';
