
<?php  //$this->load->view("admin/inheader"); ?>
<?php // $this->load->view("admin/insidebar"); 
$listingdate=isset($_REQUEST['listingdatre'])?$_REQUEST['listingdatre']:'';
if($listingdate==''){
    $listingdate=date('d/m/Y');
}
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
/*
// File used to coonect database
include("../includes/connect.php");
$token_no =strip_tags(htmlentities(htmlspecialchars($_REQUEST['token_no'])));
$token_year=strip_tags(htmlentities(htmlspecialchars($_REQUEST['token_year'])));
$filing_no =$token_no.$token_year;
function trimZero($str)
{
	$len = strlen($str);
	$newstr = "";
	for($i=0;$i<=$len;$i++)
	{
		if(substr($str,0,1) == 0)
		{
			$strlen = strlen($str);
			$str = substr($str,1,($strlen-1));
		}
	}
	return $str;
}
 */
?>

<html>
<head>

<script type="text/javascript" src="../includes/highslide/highslide-with-html1.js"></script>
<link rel="stylesheet" type="text/css" href="../includes/highslide/highslide.css" />



<script type="text/javascript">


hs.graphicsDir = '../includes/highslide/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';
function printPage()
{
//change("testdiv","hidden");
window.print();
}
</script>

<style>
table, td, th {
    border: 1px solid #1d99d4;
}

th {
    background-color: #074c62;
    color: white;
}
</style>
<style> 
.textbox { 
    background-color: #D7EFFF; 
    border: dotted 1px #646464; 
    outline: none; 
    padding: 4px 4px; 
} 
.textbox:hover { 
   background-color: #FF0; 
} 
.textbox:focus { 
    background-color:  
white
; 
} 
.textbox:active { 
    background-color: #EB8D00; 
}

.styleSelect select {
  background: transparent;
  width: 168px;
  padding: 5px;
  font-size: 16px;
  line-height: 1;
  border: 0;
  border-radius: 0;
  height: 34px;
  -webkit-appearance: none;
  color: #000;
}

.styleSelect {
  width: 150px;
  height: 25px;
  overflow: hidden;
  background: url("downArrow.png") no-repeat right #fff;
  border: 1px solid #000;
}

label {
color: #B4886B;
font-weight: bold;
display: block;
width: 150px;
float: left;
}
label:after { content: ": " }
</style>

<script language="javascript" type="text/javascript">
<!--

var win=null;
function NewWindow(mypage,myname,w,h,scroll,pos){
if(pos=="random"){LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;}
if(pos=="center"){LeftPosition=(screen.width)?(screen.width-w)/2:100;TopPosition=(screen.height)?(screen.height-h)/2:100;}
else if((pos!="center" && pos!="random") || pos==null){LeftPosition=0;TopPosition=20}
settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=yes,directories=no,status=yes,menubar=no,toolbar=no,resizable=yes';
win=window.open(mypage,myname,settings);}
// -->
</script>
	<title>
		Case Type Directory
	</title>
<link rel="stylesheet" href="style.css" type="text/css">
<link rel="stylesheet" href="../includes/style.css" type="text/css">
<!--
<link rel="stylesheet" href="../includes/styles/calendar.css" type="text/css">
<script language="JavaScript" src="../includes/javascript/simplecalendar.js" type="text/javascript"></script>
-->
<!-- ==============================start virtual key Board==================-->

<SCRIPT type="text/javascript" src="../includes/vkboards.js"></SCRIPT>

<SCRIPT type="text/javascript">

   // Parts of the following code are taken from the DocumentSelection
   // library (http://debugger.ru/projects/browserextensions/documentselection)
   // by Ilya Lebedev. DocumentSelection is distributed under LGPL license
   // (http://www.gnu.org/licenses/lgpl.html).
$casenumber
   // 'insertionS' and 'insertionE' contain the start and end
   // positions of current selection.
   //
   var opened = false, vkb = null, text = null, insertionS = 0, insertionE = 0;

   var userstr = navigator.userAgent.toLowerCase();
   var safari = (userstr.indexOf('applewebkit') != -1);
   var gecko  = (userstr.indexOf('gecko') != -1) && !safari;
   var standr = gecko || window.opera || safari;

   function keyb_change()
   {
     document.getElementById("switch").innerHTML = (opened ? "Show keyboard" : "Hide keyboard");
     opened = !opened;

     if(opened && !vkb)
     {
       // Note: all parameters, starting with 3rd, in the following
       // expression are optional; their values are equal to the
       // default parameter values for the VKeyboard object.
       // The only exception is 18th parameter (flash switch),
       // which is false by default.

       vkb = new VKeyboard("keyboard",    // container's id
                           keyb_callback, // reference to the callback function
                           true,          // create the arrow keys or not? (this and the following params are optional)
                           true,          // create up and down arrow keys? 
                           false,         // reserved
                           true,          // create the numpad or not?
                           "",            // font name ("" == system default)
                           "14px",        // font size in px
                           "#FFF",        // font color
                           "#F00",        // font color for the dead keys
                           "#000",        // keyboard base background color
                           "#000",        // keys' background color
                           "#DDD",        // background color of switched/selected item
                           "#777",        // border color
                           "#000",        // border/font color of "inactive" key (key with no value/disabled)
                           "#000",        // background color of "inactive" key (key with no value/disabled)
                           "#F77",        // border color of the language selector's cell
                           true,          // show key flash on click? (false by default)
                           "#CC3300",     // font color during flash
                           "#FF9966",     // key background color during flash
                           "#CC3300",     // key border color during flash
                           false,         // embed VKeyboard into the page?
                           true,          // use 1-pixel gap between the keys?
                           0);            // index(0-based) of the initial layout
     }
     else
       vkb.Show(opened);

     text = document.getElementById("textfield");
     text.focus();

     if(document.attachEvent)
       text.attachEvent("onblur", backFocus);
   }

   function backFocus()
   {
     if(opened)
     {
       setRange(text, insertionS, insertionE);

       text.focus();
     }
   }

   // Advanced callback function:
   //
   function keyb_callback(ch)
   {
     var val = text.value;

     switch(ch)
     {
       case "BackSpace":$casenumber
         if(val.length)
         {
           var span = null;

           if(document.selection)
             span = document.selection.createRange().duplicate();

           if(span && span.text.length > 0)
           {
             span.text = "";
             getCaretPositions(text);
           }
           else
             deleteAtCaret(text);
         }

         break;

       case "<":
         if(insertionS > 0)
           setRange(text, insertionS - 1, insertionE - 1);

         break;

       case ">":
         if(insertionE < val.length)
           setRange(text, insertionS + 1, insertionE + 1);

         break;

       case "/\\":
         if(!standr) break;

         var prev  = val.lastIndexOf("\n", insertionS) + 1;
         var pprev = val.lastIndexOf("\n", prev - 2);
         var next  = val.indexO$casenumberf("\n", insertionS);

         if(next == -1) next = val.length;
         var nnext = next - insertionS;

         if(prev > next)
         {
           prev  = val.lastIndexOf("\n", insertionS - 1) + 1;
           pprev = val.lastIndexOf("\n", prev - 2);
         }

         // number of chars in current line to the left of the caret:
         var left = insertionS - prev;

         // length of the prev. line:
         var plen = prev - pprev - 1;

         // number of chars in the prev. line to the right of the caret:
         var right = (plen <= left) ? 1 : (plen - left);

         var change = left + right;
         setRange(text, insertionS - change, insertionE - change);
$casenumber
         break;

       case "\\/":
         if(!standr) break;

         var prev  = val.lastIndexOf("\n", insertionS) + 1;
         var next  = val.indexOf("\n", insertionS);
         var pnext = val.indexOf("\n", next + 1);

         if( next == -1)  next = val.length;
         if(pnext == -1) pnext = val.length;

         if(pnext < next) pnext = next;

         if(prev > next)
            prev  = val.lastIndexOf("\n", insertionS - 1) + 1;

         // number of chars in current line to the left of the caret:
         var left = insertionS - prev;

         // length of the next line:
         var nlen = pnext - next;

         // number of chars in the next line to the left of the caret:
         var right = (nlen <= left) ? 0 : (nlen - left - 1);

         var change = (next - insertionS) + nlen - right;
         setRange(text, insert$casenumberionS + change, insertionE + change);

         break;

       default:
         insertAtCaret(text, (ch == "Enter" ? (window.opera ? '\r\n' : '\n') : ch));
     }
   }

   // This function retrieves the position (in chars, relative to
   // the start of the text) of the edit cursor (caret), or, if
   // text is selected in the TEXTAREA, the start and end positions
   // of the selection.
   //
   function getCaretPositions(ctrl)
   {
     var CaretPosS = -1, CaretPosE = 0;

     // Mozilla way:
     if(ctrl.selectionStart || (ctrl.selectionStart == '0'))
     {
       CaretPosS = ctrl.selectionStart;
       CaretPosE = ctrl.selectionEnd;

       insertionS = CaretPosS == -1 ? CaretPosE : CaretPosS;
       insertionE = CaretPosE;
     }
     // IE way:
     else if(document.selection && ctrl.createTextRange)
     {
       var start = end = 0;
       try
       {
         start = Math.abs(document.selection.createRange().moveStart("character", -10000000)); // start

         if (start > 0)
         {
           try
           {
             var endReal = Math.abs(ctrl.createTextRange().moveEnd("character", -10000000));

             var r = document.body.createTextRange();
             r.moveToElementText(ctrl);
             var sTest = Math.abs(r.moveStart("character", -10000000));
             var eTest = Math.abs(r.moveEnd("character", -10000000));

             if ((ctrl.tagName.toLowerCase() != 'input') && (eTest - endReal == sTest))
               start -= sTest;
           }
           catch(err) {}
         }
       }
       catch (e) {}
$casenumber
       try
       {
         end = Math.abs(document.selection.createRange().moveEnd("character", -10000000)); // end
         if(end > 0)
         {
           try
           {
             var endReal = Math.abs(ctrl.createTextRange().moveEnd("character", -10000000));

             var r = document.body.createTextRange();
             r.moveToElementText(ctrl);
             var sTest = Math.abs(r.moveStart("character", -10000000));
             var eTest = Math.abs(r.moveEnd("character", -10000000));

             if ((ctrl.tagName.toLowerCase() != 'input') && (eTest - endReal == sTest))
              end -= sTest;
           }
           catch(err) {}
         }
       }
       catch (e) {}

       insertionS = start;
       insertionE = end
     }
   }

   function setRange(ctrl, start, end)
   {
     if(ctrl.setSelectionRange) // Standard way (Mozilla, Opera, Safari ...)
     {
       ctrl.setSelectionRange(start, end);
     }
     else // MS IE
     {
       var range;

       try
       {
         range = ctrl.createTextRange();
       }
       catch(e)
       {
         try
         {
           range = document.body.createTextRange();
           range.moveToElementText(ctrl);
         }
         catch(e)
         {
           range = null;
         }
       }

       if(!range) return;

       range.collapse(true);
       range.moveStart("character", start);
       range.moveEnd("character", end - start);
       range.select();
     }

     insertionS = start;
     insertionE = end;
   }

   function deleteSelection(ctrl)
   {
     if(insertionS == insertionE) return;

     var tmp = (document.selection && !window.opera) ? ctrl.value.replace(/\r/g,"") : ctrl.value;
     ctrl.value = tmp.substring(0, insertionS) + tmp.substring(insertionE, tmp.length);

     setRange(ctrl, insertionS, insertionS);
   }

   function deleteAtCaret(ctrl)
   {
     // if(insertionE < insertionS) insertionE = insertionS;
     if(insertionS != insertionE)
     {
       deleteSelection(ctrl);
       return;
     }

     if(insertionS == insertionE)
       insertionS = insertionS - 1;

     var tmp = (document.selection && !window.opera) ? ctrl.value.replace(/\r/g,"") : ctrl.value;
     ctrl.value = tmp.substring(0, insertionS) + tmp.substring(insertionE, tmp.length);

     setRange(ctrl, insertionS, insertionS);
   }

   // This function inserts text at the caret position:
   //
   function insertAtCaret(ctrl, val)
   {
     if(insertionS != insertionE) deleteSelection(ctrl);

     if(gecko && document.createEvent && !window.opera)
     {
       var e = document.createEvent("KeyboardEvent");

       if(e.initKeyEvent && ctrl.dispatchEvent)
       {
         e.initKeyEvent("keypress",        // in DOMString typeArg,
                        false,             // in boolean canBubbleArg,
                        true,              // in boolean cancelableArg,
                        null,              // in nsIDOMAbstractView viewArg, specifies UIEvent.view. This value may be null;
                        false,             // in boolean ctrlKeyArg,
                        false,             // in boolean altKeyArg,
                        false,             // in boolean shiftKeyArg,
                        false,             // in boolean metaKeyArg,
                        null,              // key code;
                        val.charCodeAt(0));// char code.

         ctrl.dispatchEvent(e);
       }
     }
     else
     {
       var tmp = (document.selection && !window.opera) ? ctrl.value.replace(/\r/g,"") : ctrl.value;
       ctrl.value = tmp.substring(0, insertionS) + val + tmp.substring(insertionS, tmp.length);
     }

     setRange(ctrl, insertionS + val.length, insertionS + val.length);
   }

 </SCRIPT>

<!--============End virtual keyboard=============--> 

</head>
<script language="javascript">

// WRITE YOUR CODE HERE

function buttondown(str,str1)
{
	with(document.frm)
	{
		var len = str1.length;
		if(len < 15)
		{
			case_no.value = case_no.value + str;
		}
		else
		{
			/*var rgyearstr = rgyear.value;
			//var len1 = rgyearstr.length;
			if(len1 < 4)
			{
				rgyear.value = rgyear.value + str;
			}*/
		}
	}
}

//=======================================

function trim(inputString) {
	if (typeof inputString != "string") { return inputString; }
	var retValue = inputString;
	var ch = retValue.substring(0, 1);
	while (ch == " ")
	{
		retValue = retValue.substring(1, retValue.length);
		ch = retValue.substring(0, 1);
	}
	ch = retValue.substring(retValue.length-1, retValue.length);
	while (ch == " ")
	{
		retValue = retValue.substring(0, retValue.length-1);
		ch = retValue.substring(retValue.length-1, retValue.length);
	}
	while (retValue.indexOf("  ") != -1)
	{
		retValue = retValue.substring(0, retValue.indexOf("  ")) +
		retValue.substring(retValue.indexOf("  ")+1, retValue.length);
	}
	return retValue;
}
function checkInteger(str)
{
	var len = str.length;
	for(var i=0; i < len; i++)
	{
		if(str.charAt(i) == ".")
		{
			return false;
		}
	}
}

//------------------------------------------------------>

function submitForm()
{
 	with(document.frm)
	{		
	 action = "casestatus";
	submit();
	}
}

function submitForm1()
{
 	with(document.frm)
	{	
		if(token_no.value == "")
		{
			alert("Plaese enter Token no");
			token_no.focus();
			return false;
		}
		if(isNaN(token_no.value) == true)
		{
			alert("Please enter numeric value");
			token_no.select();
			return false;
		}
		if(token_year.value == "")
		{
			alert("Plaese enter Token Year");
			token_year.focus();
			return false;
		}
		if(isNaN(token_year.value) == true)
		{
			alert("Please enter numeric value");
			token_year.select();
			return false;
		}	
		action = "case_detail_report.php";
		submit();
	}
}


function submitForm2()
{
 	with(document.frm)
	{	
		if(case_type.value ==0)
		{
			alert("Please select case type");
			case_type.focus();
			return false;
		}

		if(case_no.value == "")
		{
			alert("Plaese enter case no");
			case_no.focus();
			return false;
		}
		if(isNaN(case_no.value) == true)
		{
			alert("Please enter numeric value");
			case_no.select();
			return false;
		}
		if(checkInteger(case_no.value)== false)
		{
			alert("Please enter integer");
			case_no.focus();
			return false;
		}
		if(case_year.value == "")
		{
			alert("Please enter year");
			case_year.focus();
			return false;
		}
		if(isNaN(case_year.value) == true)
		{
			alert("Please enter numeric value");
			case_year.select();
			return false;
		}
		
		action = "case_detail_report.php";
		submit();
	}
}


function submitForm3()
{
 	with(document.frm)
	{		
		if(search_name.value=='')
		{
			alert("Please Enter name");
			search_name.focus();
			return false;
		}
	 	action = "party_wise_action.php";
		submit();
	}
}

function validate()
{
	with(document.frm)
	{
	
		if(app_type.options[app_type.selectedIndex].value == "")
		{
			alert("Please select search by");
			app_type.focus();
			return false;
		}

	if(pet_name.value == "")
		{
			alert("Please enter valid search criteria . Valid Token No Or Case No");
			pet_name.focus();
			return false;
		}
	if(res_name.value == "")
		{
			alert("Please enter valid search criteria. Valid Token No Or Case No");
			res_name.focus();
			return false;
		}

	action = "case_detail_report_action2.php";
	submit();
	document.frm.submit1.disabled = true;  
       	document.frm.submit1.value = 'Please Wait...';  
       	return true;


	}

}





//  END
</script>
<?php

$case_number=set_value("case_number");
$filing_no=set_value("filing_no");
$app_type=set_value("app_type");
if($app_type=='')
{   $app_type='dno'; }
$pet_name=set_value("pet_name");
$res_name=set_value("res_name");
$token_no=set_value("token_no");
$token_year=set_value("token_year");
$curYear=date('Y');
$y=set_value("y");
?>
<body onLoad="keyb_change()">
<!--?php include_once('../includes/inheader.php');?-->
<!--?php include_once('../includes/common_css.php');?-->

<tr><td valign="top">
<form name="frm" method="post" action="">
<input type="hidden" name="frmAction" value="add">
<input type="hidden" name="case_number" value="<?php print htmlentities(htmlspecialchars($case_number)); ?>">
 <input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no)); ?>"> 

<table width="90%" cellspacing="1" cellpadding="5" align="center" border="0" >
<tr><th colspan="7" align="center"><b><font face="Verdana,  Helvetica, sans-serif" size="3"><u>CASE STATUS</u></b></th></tr>
<tr><td colspan="7" align="center"><b><span class="error">
<?php
/*if(!$msg == "")
{
	print "$msg";
}*/
?>
</span></b>
</td>
</tr>
<tr>
<td align="right"><font face="Verdana" size="2">
<span class="error">*</span>Search By</font>
<td align="left">
<select name="app_type" onChange="return submitForm();" style="width:100px">
<option value="">Select</option>
<option <?php if($app_type=="dno") { ?> selected <?php } ?> value="dno">Diary No</option>
<option <?php if($app_type=="cno") { ?> selected <?php } ?> value="cno">Case No</option>

</select> 
</div>
</td>
</tr>

<?php 
if($app_type=="dno" )
{
?>
<tr>
<td height="30" align="center" cellpadding="5" colspan="7"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="black">  
<b>
<?php
echo "<u>SEARCH BY DIARY NUMBER</u>";
?>
</font>
</td>    
</tr>
<tr>
	<td align="right" width="100"><font face="Verdana" size="2">
		<span class="error">*</span> Diary Number</font>
	</td>

	<td align="left" colspan="6">
		<input  id="1" type="text"  size="6" maxlength="6" class="textbox" name="token_no" value="<?php print htmlentities(htmlspecialchars($token_no)); ?>">
	
		<input  id="1" type="text" size="4" maxlength="4" class="textbox" name="token_year" value="<?php if($token_year == ''){echo htmlentities(htmlspecialchars($curYear));}else {echo htmlentities(htmlspecialchars($token_year));} ?>">

          	
	
<input type="submit" name="button1"  value=" Go" size="20" onClick="return submitForm();">
	
</td>
</tr>

<?php } ?>



<?php

if($app_type=="cno")
{  
?>
<tr>
<td height="30" align="center" cellpadding="5" colspan="7"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="black">  
<b>
<?php
echo "<u>SEARCH BY CASE TYPE/ CASE NUMBER / CASE YEAR</u>";
?> 
</font>
</td>    
</tr>

<tr>
<td align="right"><font face="Verdana" size="2">
<span class="error">*</span>Case Nature</td>
<td align="left" colspan="6"><select name="case_type" style="width:100px" >
<option value="0">select</option>
<?php
$display='Y';
$sql_cp="select * from case_type_master where display=? order by case_type_name asc";
$sql_cp = $dbh->prepare($sql_cp);
$sql_cp->bindParam(1, $display, PDO::PARAM_STR);
//$sql_cp->bindParam(2, $court_no, PDO::PARAM_INT);
$sql_cp->execute();
while($row = $sql_cp-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{

  $casetypecode=$row['case_type_code'];
 if($case_type == $casetypecode)
                {
		print "<option value=".htmlentities(htmlspecialchars($row['case_type_code']))." selected>".htmlentities(htmlspecialchars($row['short_name']))."</option>";
                }
           else
                {
                print "<option value=".htmlentities(htmlspecialchars($row['case_type_code'])).">".htmlentities(htmlspecialchars($row['short_name']))."</option>";
		}
 }
 ?>

</select>
</td>
</tr>
<tr>
<td align="right">
<font face="Verdana" size="2">


	<span class="error">*</span>Case No
</td>
<td>
	<input type="text"  maxlength="7" class="textbox" size="8" name="case_no" value="<?php print htmlentities(htmlspecialchars($case_no)); ?>" >
</td>

<td align="right">	
<font face="Verdana" size="2"><span class="error">*</span>Year
</td>
<td>	
<input type="text" class="textbox" maxlength="4" size="3" name="case_year" value="<?php print htmlentities(htmlspecialchars($case_year)); ?>" id="textfield"> 
</td>
<td>	
<input type="button" name="button"  value=" Go" size="20" onClick="return submitForm2();">
	
</td>

</tr>
<?php

//-------------------------------------------------------------------------------------------
$clen = strlen($case_type);

$clength = 3-$clen;
for($c=0;$c<$clength;$c++)
$case_type = "0".$case_type;

$clen = strlen($case_no);
$clength = 7-$clen;
for($c=0;$c<$clength;$c++)
$case_no = "0".$case_no;
if($case_no == 000000)
$case_no='';	

$chr=4;// char for first hard code digit of filing no
	$c_no=$chr.$case_type.$case_no.$case_year;
$sq2="select * from case_detail where  status IN ( 'P','D') and case_no=?";
$sql_cp = $dbh->prepare($sq2);
$sql_cp->bindParam(1, $c_no, PDO::PARAM_STR);
$sql_cp->execute();
	while ($rw2 = $sql_cp-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
	
	{     $filing_no=$rw2['filing_no'];
	      $pt_name=$rw2['pet_name'];
	      $rs_name=$rw2['res_name'];
	}
	?><input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no)); ?>">
	<?php
	$party_name=$pt_name."  Vs. ".$rs_name;
	if($rs_name==''){$party_name='';}

//------------------------------------------------------------------------------------------
}
?>
<?php 
if(!empty($filedcase))
{
?>

<tr><td align="right" ><font face="Verdana" size="2"><b>Applicant Name</td>
<td valign="top">	<?php  echo  $filedcase['pet_name'];?></td>
<td align="right" ><font face="Verdana" size="2"><b>Respondent Name</td>
<td colspan="7"><?php  echo  $filedcase['res_name'];?>
</tr>
<tr><td align="right" ><font face="Verdana" size="2"><b>Filing Date</td>
<td colspan="7"><?php  echo  $filedcase['dt_of_filing'];?>
</tr>
<tr><td align="right" ><font face="Verdana" size="2"><b>Next Listing Date</td>
<td valign="top">	<?php  echo  $casealo['listing_date'];?></td>
		
</tr>
<tr>
		<th><font face="Verdana, Arial, Helvetica, sans-serif" size="2" align="left">Filed By</font></th>
		<th><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Document Type</font></th>
		<th><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Upload Date</font></th>
		<th><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Document name</font></th>
		</tr>
	<?php foreach($docs as $docdtl): ?>
		<tr>
			<td valign="top">	<?php  echo  $docdtl['document_filed_by'];?></td>
				<td><?php echo $docdtl['document_type']; ?></td>
				<td><?php echo $docdtl['update_on']; ?></td>
				<!--td><?php echo $docdtl['file_url']; ?></td-->
				<!--td><a href="<?php echo $docdtl['file_url']; ?>"><?php echo "view"; ?> </a></td-->
				<!--td><a href="<?=base_url('scrutiny')?>?del=delete&ob_code=<?php echo $user['objection_code'];?>&search=<?php echo $casedtl['filing_no'];?>&objection_status=Y" onClick="return confirm('Are you sure to delete this objection?');"><img src="<?=base_url('img/delete.gif')?>" border=0></a>
				</td-->
				<td><a href="<?php echo $docdtl['file_url']; ?>" target="popup" 
				onclick="window.open('<?php echo $docdtl['file_url']; ?>','popup','width=800,height=600'); return false;">View</a></td>
			</tr>
	<?php endforeach; ?>
<?php } else { ?>
<tr><td colspan="6"><font color='red' size='2'><center>NOTE : Please Enter Valid Case Search Parameters.</center></td></tr>
 <?php } ?>

</table>
</form>
</td></tr></table>
<br><br><br><br>
  

</body>
</html>

<?php //$this->load->view("admin/infooter"); ?>		
<script>
function serchDFR(){
	with(document.caselistingsub){
	action = base_url+"cause_list";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}
</script>