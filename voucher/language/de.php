<?php

/*
NAME de.php is part of the voucher4guests Project
SYNOPSIS german text file 
DESCRIPTION german language text file  
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE 

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see 
http://www.gnu.org/licenses/gpl-2.0.html
*/



### index.php ###
$lang['PageTitle']='MPI';
$lang['HeaderTitle']='Login - G&auml;ste Netzwerk';
$lang['LableVoucherCode']='Voucher-Code :';
$lang['AcceptPolicy']='Ich akzeptiere die <a href="agree.php?lang=de" target="_blank">Nutzerordnung</a>.';
$lang['Submit']='Absenden';
$lang['Help']='Hilfe';
$lang['IndexContent']='<h2>Voucher</h2>
          <p>Willkommen auf der <strong>G&auml;stenetzwerk</strong> Loginseite. 
             Bitte geben Sie ihren zwanzigstelligen Voucher ein. Wenn Sie 
             keinen Voucher haben oder Ihr Voucher abgelaufen ist, bekommen 
             Sie einen neuen Voucher an der Rezeption.
          </p>';
$lang['IndexAddContent']='<h2>zus&auml;tzlicher Inhalt</h2>
          <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam 
             nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
             sed diam voluptua. At vero eos et accusam et justo duo
          </p>';          
$lang['LinkValidity']='Laufzeit des Vouchers anzeigen / Ausloggen';
$lang['Footer']='&copy; 2011, Max-Planck-Institut f&uuml;r ... , Ort';
# ERRORS #
$lang['InvalidVoucher']='Ung&uuml;ltiger Voucher!';
$lang['ActivatedVoucher']='Ihr Voucher wurde bereits aktiviert!';
$lang['ExpiredVoucher']='Ihr Voucher ist abgelaufen!';
$lang['AcceptAgreement']='Bitte akzeptieren Sie die Nutzerordnung!';
$lang['MacError']='FEHLER mit ihrer MAC-Adresse!';
$lang['NotActivatedVoucher']='Dieser Voucher ist nicht aktiviert!';
$lang['EnterCompletely']='Bitte Voucher-Code komplett eingeben!';
$lang['ActivationCompleted']='Freischaltung abgeschlossen!';


### agree.php ###
$lang['AgreeContent']='
          <h2>Nutzungsordnung f&uuml;r den Zugang und die Nutzung des G&auml;stenetzes am Max-Planck-Institut f&uuml;r ...</h2>
          <h3>1. Geltungsbereich</h3>
          <ol>
             <li>Diese Regelungen gelten sowohl f&uuml;r institutsfremde Rechner, die Zugang zum G&auml;stenetz haben, 
                 als auch f&uuml;r vom Institut zur Verf&uuml;gung gestellte EDV-Ressourcen, mittels derer auf das G&auml;stenetz 
                 zugegriffen wird. Der Anschluss eines institutsfremden Rechners an das G&auml;stenetz ist nur mit Erlaubnis des Instituts zul&auml;ssig.</li>
             <li>Diese Nutzungsordnung ist von allen Nutzern der G&auml;stenetze zu beachten, unabh&auml;ngig von der Art
                 ihres Rechtsverh&auml;ltnisses zur Max-Planck-Gesellschaft (MPG).</li>
          </ol>

          <h3>2. Zugangsberechtigung</h3>
          <ol>
             <li>Es besteht kein Rechtsanspruch auf Zugang zum G&auml;stenetz bzw. zur Nutzung von vom Institut zur 
                 Verf&uuml;gung gestellten EDV-Ressourcen, die sich im G&auml;stenetz des Instituts befinden. </li>
             <li>Verst&ouml;&szlig;t der Nutzer gegen diese Nutzerordnung, kann die Zugangsberechtigung dauerhaft oder vor&uuml;bergehend
                 entzogen oder nachtr&auml;glich beschr&auml;nkt werden. Dem Betroffenen soll vor dem Entzug der Nutzungsberechtigung 
                 Gelegenheit zur Stellungnahme gegeben werden.</li>
          </ol>
          <h3>3. Pflichten der Nutzer</h3>
          <ol>
             <li>Der Nutzer hat jedes rechtswidrige Nutzungsverhalten zu unterlassen (z.B. Down- oder Upload von urheberrechtlich
                 gesch&uuml;tztem Material). Au&szlig;erdem hat der Nutzer jede Handlung zu unterlassen, welche geeignet ist, Interessen und 
                 Ansehen der MPG oder Dritter zu verletzen oder der MPG oder Dritten Schaden zuzuf&uuml;gen (z.B. Down- oder Upload 
                 von Pornographie).</li>        
             <li>Die vom Institut zur Verf&uuml;gung gestellten EDV-Ressourcen sind pfleglich zu behandeln. Ver&auml;nderungen der 
                 Installation und Konfiguration der Rechner und des Netzwerkes, Manipulationen an der Hardwareausstattung sowie 
                 Eingriffe in die Softwareinstallation sind untersagt. Beim Auftreten von Funktionsst&ouml;rungen oder schwerwiegenden 
                 Fehlern ist unverz&uuml;glich die EDV-Abteilung zu verst&auml;ndigen.</li>
             <li>Der Nutzer ist verpflichtet, seinen institutsfremden Rechner durch einen aktuellen Virenscanner sowie eine 
                 Firewall vor Schadprogrammen zu sch&uuml;tzen. Der Nutzer hat dies auf Verlangen nachzuweisen. Die EDV-Abteilung 
                 stellt bei Bedarf die entsprechende Schutzsoftware im Sinne des Satzes 1 dieses Absatzes zur Verf&uuml;gung.</li> 
             <li>Der Nutzer ist verpflichtet, seinen institutsfremden Rechner im G&auml;stenetz ausschlie&szlig;lich mittels der Zugangskennung
                 (Gast-Login), deren Nutzung ihm im Rahmen der Zugangsberechtigung gestattet wurde, zu nutzen und die Zugangskennung 
                 nicht Dritten zur Nutzung zu &uuml;berlassen. Die Nutzung des G&auml;stenetzes mittels vom Institut zur Verf&uuml;gung gestellter 
                 EDV-Ressourcen darf ausschlie&szlig;lich mittels der Zugangskennung (Gast-Login), deren Nutzung ihm im Rahmen der 
                 Zugangsberechtigung gestattet wurde, oder Rahmen der vom Institut vorgegebenen Konfiguration genutzt werden.</li>
             <li>Der Nutzer ist insbesondere verpflichtet,
                <ol class="agree">
                   <li>das G&auml;stenetz nur nach Ma&szlig;gabe dieser Nutzungsordnung zu nutzen,</li>
                   <li>alles zu unterlassen, was einen ordnungsgem&auml;&szlig;en Betrieb st&ouml;rt,</li>
                   <li>es zu unterlassen, kostenpflichtige Dienste auf Rechnung der MPG zu nutzen,</li>
                   <li>keine aus dem Internet erreichbaren Dienste anzubieten,</li>
                   <li>bei der Nutzung des G&auml;stenetzes die gesetzlichen/vertraglichen Regelungen zum Schutz der Rechte Dritter zu beachten. 
                       Insbesondere ist die Nutzung von Peer-to-Peer-Diensten, Filehostern/Sharehostern oder Usenet-Anbietern zur Verbreitung 
                       oder zum Download von urheberrechtlich gesch&uuml;tzten Inhalten untersagt,</li>
                   <li>die gesetzlichen Bestimmungen insbesondere des Strafrechts, Urheberrechts und des Jugendschutzrechts zu beachten.</li>
                </ol> 
             </li>
          </ol>
 
          <h3>4. Protokollierung und Auswertung</h3>
          <ol>
             <li>Folgende Daten zum Nutzungsverhalten werden protokolliert: Nutzerkennung und/oder MAC-Adresse, IP-Adressen des Absenders 
                 und Empf&auml;ngers, Art des genutzten Dienstes (z.B. http, ftp, telnet) sowie Zeitstempel.</li>
             <br />          
                 Die Aufbewahrungsfrist der Protokolle betr&auml;gt zwei Monate. Die Aufbewahrungsfrist verl&auml;ngert sich, 
                 wenn Tatsachen den Verdacht eines Missbrauchs des G&auml;stenetzes begr&uuml;nden. In diesem Fall werden die erforderlichen 
                 Protokolle bis zur Kl&auml;rung des Verdachts bzw. bis zum Abschluss der Ermittlungen aufbewahrt.</li>
             <li>Die Auswertung der protokollierten Daten erfolgt, soweit dies erforderlich ist,
                <ol class="agree">
                   <li>zur Gew&auml;hrleistung eines ordnungsgem&auml;&szlig;en Systembetriebs,</li>
                   <li>zur Ressourcenplanung und Systemadministration,</li>
                   <li>zum Schutz der personenbezogenen Daten anderer Nutzer,</li>
                   <li>zu Abrechnungszwecken,</li>
                   <li>f&uuml;r das Erkennen und Beseitigen von St&ouml;rungen,</li>
                   <li>zur Missbrauchsbek&auml;mpfung, sofern tats&auml;chliche Anhaltspunkte daf&uuml;r vorliegen,
                       dass der Nutzer das G&auml;stenetz entgegen der Vorgaben dieser Nutzungsordnung nutzt/genutzt hat.</li>
                </ol>
             </li>
          </ol> 

          <h3>5. Haftung der Nutzer</h3>
          <ol>
             <li>Der Nutzer haftet f&uuml;r alle Sch&auml;den und Nachteile, die der MPG durch eine von ihm zu vertretende missbr&auml;uchliche oder 
                 rechtswidrige Verwendung des zur Verf&uuml;gung gestellten G&auml;stenetzes - mittels eines institutsfremden Rechners oder der 
                 vom Institut zur Verf&uuml;gung gestellten EDV-Ressourcen - bzw. dadurch entstehen, dass der Nutzer schuldhaft seinen Pflichten 
                 aus dieser Nutzungsordnung nicht nachkommt.</li>          
             <li>Der Nutzer haftet f&uuml;r alle Handlungen Dritter, sofern er diese durch einen von ihm schuldhaft zu vertretenden Versto&szlig; 
                 gegen diese Nutzerordnung erm&ouml;glicht hat.</li>              
             <li>Die Ersatzpflicht nach den Abs&auml;tzen 1 und 2 tritt nicht ein, wenn der Nutzer die Vorgaben dieser Nutzerordnung mit der 
                 im Verkehr erforderlichen Sorgfalt beachtet hat bzw. wenn der Schaden oder Nachteil auch bei Anwendung dieser Sorgfalt entstanden w&auml;re.</li>          
             <li>Der Nutzer hat die MPG von allen Anspr&uuml;chen freizustellen, die Dritte gegen die MPG aufgrund einer schuldhaften Verletzung 
                 seiner Pflichten aus dieser Nutzungsordnung geltend machen.</li>          
             <li>Im Falle einer bereits durch die MPG abgegebenen strafbewehrten Unterlassungserkl&auml;rung ist auch die Vertragsstrafe umfasst, 
                 die durch ein Verhalten anf&auml;llt, welche dem Nutzer nach den Abs&auml;tzen 1 und 2 zuzurechnen ist.</li>         
             <li>Die MPG beh&auml;lt sich die Einleitung rechtlicher Schritte gegen Nutzer, die gegen Vorgaben aus dieser Nutzungsordnung versto&szlig;en, vor.</li>
         </ol>

          <h3>6. Haftungsbeschr&auml;nkung des Max-Planck-Instituts</h3>
          <ol>
            <li>Der Zugang zum G&auml;stenetz - unabh&auml;ngig davon, ob dieser mittels eines institutsfremden Rechners oder der vom Institut zur Verf&uuml;gung 
                gestellten EDV-Ressourcen erfolgt - erfolgt ohne Gew&auml;hr, insbesondere ohne ausdr&uuml;ckliche oder stillschweigende Garantie daf&uuml;r, 
                dass das G&auml;stenetz jederzeit vollumf&auml;nglich oder fehlerfrei verf&uuml;gbar ist. </li>         
            <li>Das Institut haftet nicht f&uuml;r Sch&auml;den - weder unmittelbare noch mittelbare -, die im Zusammenhang mit der Nutzung entstehen, es sei 
                denn, dem Institut f&auml;llt Vorsatz oder grobe Fahrl&auml;ssigkeit zur Last. Die Haftungsbeschr&auml;nkung gilt nicht bei Sch&auml;den aus der 
                Verletzung des Lebens, des K&ouml;rpers oder der Gesundheit, die auf einer fahrl&auml;ssigen Pflichtverletzung des Instituts oder einer 
                vors&auml;tzlichen oder fahrl&auml;ssigen Pflichtverletzung eines gesetzlichen Vertreters oder Erf&uuml;llungsgehilfen des Instituts beruhen.</li>
          </ol>';
$lang['AgreePDF']='Nutzerordnung.pdf';

### validity.php ###
$lang['ValidityContent']='<h2>Ablauf Datum</h2>
          <h3>Anzeigen bis wann Ihr Ger&auml;t aktiviert ist.</h3>';
$lang['ShowValidity']='<p>Ihr Voucher l&auml;uft am <strong>%1$s</strong> ab!</p>
           <p><strong>Voucher: </strong>%2$s</p>
           <p>Der Voucher wurde mit dieser<br /> MAC-Adresse: <i>%3$s</i> aktiviert.</p>';
$lang['NoValidity']='</p>&rArr; Ihr Ger&auml;t ist zurzeit nicht mit einem Voucher registriert.</p>';

$lang['LogoutContent']='<h2>Ausloggen</h2>
          <h3>Ihr Ger&auml;t deaktivieren bevor der Ablaufzeitpunkt ihres Vouchers erreicht ist.</h3>';
$lang['Logout']='Ausloggen';
$lang['LogoutWorks']='Sie wurden erfolgreich ausgeloggt.';
$lang['NoLogout']='Sie konnten nicht ausgeloggt werden, da ihr Ger&auml;t nicht freigeschalten ist.';	
$lang['GoToStart']='Zur&uuml;ck zur Login-Seite';


##### mobil #####
$lang['SwitchView']='zur normalen Ansicht wechseln';



?>
