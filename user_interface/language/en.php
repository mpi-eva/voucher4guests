<?php

/*
NAME en.php is part of the voucher4guests Project
SYNOPSIS english text file 
DESCRIPTION english language text file  
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE 

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see 
http://www.gnu.org/licenses/gpl-2.0.html
*/


### index.php ###
$lang['PageTitle']='Guest Network - Login';
$lang['HeaderTitle']='Guest Network - Login';
$lang['LableVoucherCode']='Voucher-Code';
$lang['Accept']='I accept the';
$lang['Policy']='User Agreement';
$lang['Submit']='Connect to network';
$lang['Help']='Help';
$lang['IndexContent']='<h3 class="first-headline">Voucher</h3>
          <p>Welcome to the <strong>Guestnetwork</strong> login page. 
             Please enter your twenty-digit voucher. If you have
             no voucher or your voucher has expired, you get a new 
             one at the reception.
          </p>';
$lang['IndexAddContent']='<h3>additional content</h3>
          <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam 
             nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
             sed diam voluptua. At vero eos et accusam et justo duo
          </p>';
$lang['LinkValidity']='show validity of the voucher / logout';
//$lang['Footer']='&copy; 2011, Max Planck Institute for ..., Ort';
$lang['Footer']='&copy; 2015, Max Planck Institute for Evolutionary Anthropology, Leipzig';
# ERRORS #
$lang['InvalidVoucher']='Invalid Voucher!';
$lang['ActivatedVoucher']='This Voucher was already activated!';
$lang['ActivatedDevice']='Your device is already activated!';
$lang['ExpiredVoucher']='This Voucher has expired!';
$lang['AcceptAgreement']='Please accept the User Agreement!';
$lang['MacError']='ERROR with your mac address!';
$lang['NotActivatedVoucher']='This Voucher is not activated!';
$lang['EnterCompletely']='Please enter the voucher completely!';
$lang['MaxDevicesActivated']='You can activate no more devices with this Voucher-Code.';
$lang['ActivationCompleted']='Activation finished!';


/* Help modal */
$lang['HelpTitle']="Help";
$lang['HelpText']="<p>Enter your twenty-digit voucher here.</p>
                <p>Please take care of upper- and lowercase characters.</p>";
$lang['HelpText1']="<p>If you have no voucher or your voucher has expired, you will get a new one at the reception.</p>";
$lang['Close']="Close";

### agree.php ###
$lang['PolicyTitle']='Regulations for Access to and Use of the Guest Network at the Max Planck Institute for ...';
$lang['Download']='Download:';
$lang['PolicyPDF']='Policy.pdf';
$lang['PolicyContent']='
          <h3>1. Area of application</h3>
          <ol>
             <li>These regulations apply both to non-Institute computers which have access to the guest network as well as to computer 
                 facilities made available by the Institute and used to access the guest network. Computers which are not the property 
                 of the Institute may only be connected to the guest network with the permission of the Institute.</li>          
             <li>All users of guest networks are obliged to comply with these regulations, irrespective of their legal relationship 
                 with the Max Planck Society (MPS).</li>
          </ol>
          <h3>2. Access rights</h3>
          <ol>
             <li>There is no legal entitlement to access guest networks or use computer facilities made available by the Institute 
                 and connected to the Institute guest network.</li>          
             <li>Users who breach these regulations are liable to have their right of access permanently or temporarily withdrawn or 
                 subsequently restricted. Persons concerned will be afforded an opportunity to respond before access is withdrawn.</li>
          </ol>
          <h3>3. Duties of users</h3>
          <ol>
             <li>Users shall refrain from any and all unlawful acts (e.g. downloading or uploading material that is protected by copyright). 
                 In addition users shall refrain from any and all acts that are likely to prejudice the interests or reputation of the MPS 
                 or third parties or otherwise cause harm to the MPS or third parties (e.g. downloading or uploading pornography).</li>          
             <li>The computer facilities made available by the Institute must be treated with care. It is prohibited to make any changes to 
                 the installation or configuration of computers and networks or manipulate the hardware or interfere in any way with the 
                 software installation. In the event of functional defects or serious faults, the IT department must be notified immediately.</li>          
             <li>Users are obliged to install an up-to-date virus scanner and a firewall to protect their non-Institute computers against 
                 malware. Users must provide proof of such protection on request. Where necessary the IT department will provide appropriate 
                 software protection within the meaning of Sentence 1 of this Paragraph. </li>          
             <li>Users are obliged to use their non-Institute computers on the network solely via the guest log-in approved in conjunction 
                 with their rights of access. This log-in must not be disclosed for use by third parties. Similarly the guest network may only 
                 be accessed from computers made available by the Institute via the guest log-in approved in conjunction with the right of 
                 access, or in the configuration specified by the Institute.</li>                 
             <li>Users are in particular obliged
                <ol class="agree">
                   <li>to use the guest network solely in accordance with these regulations;</li>
                   <li>to refrain from any action likely to interfere with due and proper operation;</li>
                   <li>to refrain from using chargeable services at the expense of the MPS;</li>
                   <li>not to offer any services that may be accessed via the Internet;</li>
                   <li>to observe the legal/contractual requirements to protect the rights of third parties when using the guest 
                       network; it is in particular prohibited to use of peer-to-peer services, file hosting/share hosting or Usenet 
                       services to distribute or download content that is protected by copyright;</li>
                   <li>to observe the provisions of the law, in particular criminal law, copyright legislation and regulations for the protection of minors.</li>
                </ol>
             </li>  
          </ol>    
          
          <h3>4. Logging and analysis</h3>
          <ol>
             <li>The following user data are logged: User ID and/or MAC address, IP addresses of sender and recipient, types of services used 
                 (e.g. http, ftp, telnet) and timestamp. Logs are retained for a period of two months. However this period will be extended if 
                 there are grounds to suspect any misuse of the guest network. In such case the requisite logs will be retained until such 
                 suspicion has been clarified and investigations completed.
         
             <li>Where necessary the data logs will be analysed for the following purposes
                <ol class="agree">
                   <li>to guarantee due and proper system operation;</li>
                   <li>for resource planning and system administration purposes;</li>
                   <li>to protect the personal data of other users;</li>
                   <li>for billing purposes;</li>
                   <li>to identify and eliminate faults;</li>
                   <li>to combat misuse if there is evidence that the guest network is being or has been used in contravention of these regulations.</li>
                </ol>
             </li>  
          </ol>  
         
          <h3>5. Liability of users</h3>
          <ol>
             <li>Users shall be liable for any loss, damage or detriment suffered by the MPS either as a result of their improper or unlawful 
                 use of the guest network - whether via a non-Institute computer or via computer facilities made available by the Institute - or 
                 due to a negligent failure on the part of users to fulfil their obligations arising from these regulations.</li>          
             <li>Users shall be liable for any acts committed by third parties insofar as the user shall have facilitated the same as a result 
                 of a negligent breach of these regulations.</li>          
             <li>Users shall not however be liable for compensation pursuant to Paras. 1 and 2 provided that they have taken commensurate care 
                 in complying with these regulations and the loss or impairment would have occurred even despite this care.</li>          
             <li>Users must indemnify the MPS against all claims made by third parties against the MPS as a result of a negligent breach of the 
                 obligations arising from these regulations.</li>          
             <li>In the event that notice is given by the MPS to cease and desist on pain of prosecution, this shall also include the imposition 
                 of such contractual penalty as shall be due in the event of conduct pursuant to Paras. 1 and 2.</li>          
             <li>The MPS reserves the right to take legal action against users who breach the terms of these regulations.</li>
          </ol>
          
          <h3>6. Limited liability on the part of the Max Planck Institute</h3>       
          <ol>
             <li>No liability is assumed for access to the guest network, irrespective of whether via a non-Institute computer or via computer 
                 facilities made available by the Institute. In particular, there is no expressed or implied guarantee that the guest network 
                 will be available at all times, either in full or without defect.</li>          
             <li>The Institute shall not be liable for any loss or damage - whether direct or indirect - in connection with such use other than 
                 in the event of wilful or gross negligence on the part of the Institute. Limited liability does not apply in case of loss of life, 
                 physical injury or impairment of health as a result of a negligent breach of duty on the part of the Institute or a wilful or 
                 negligent breach of duty by a legal representative or vicarious agent of the Institute.</li>
          </ol>';

 
### validity.php ###
$lang['ValidityHeadline']='Expiration Date';
$lang['ValidityDesc']='Shows you how long your device is activated.';
$lang['activatedTo']='Your device is activated to %s';
$lang['VoucherCode']='Voucher-Code:';
$lang['NoValidity']='You are currently not registered on a voucher.';
$lang['remainingActivations']='You can activate %s more devices with this Voucher-Code.';
$lang['noRemainingActivations']='You can activate no more devices with this Voucher-Code.';

$lang['LogoutHeadline']='Log out';
$lang['LogoutDesc']='Deactivate your device before the expiration time is reached.';
$lang['Logout']='Log out';
$lang['LogoutWorks']='Logout successful.';
$lang['NotActivated']='Your device is not activated.';
$lang['GoToStart']='go back to login page';

?>
