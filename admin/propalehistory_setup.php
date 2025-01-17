<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) 2015 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 	\file		admin/doc2project.php
 * 	\ingroup	doc2project
 * 	\brief		This file is an example module setup page
 * 				Put some comments here
 */

// Dolibarr environment
require '../config.php';
// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";

// Translations
$langs->load("propalehistory@propalehistory");

// Access control
if (! $user->admin) {
    accessforbidden();
}

// Parameters
$action = GETPOST('action', 'alpha');

/*
 * Actions
 */
if (preg_match('/set_(.*)/',$action,$reg))
{
	$code=$reg[1];
	if ($code == 'PROPALEHISTORY_AUTO_ARCHIVE' && ! empty($conf->global->PROPALEHISTORY_ARCHIVE_ON_MODIFY)) {
		$mesg = $langs->trans('PropalHistoryAutoArchiveAndArchiveOnModifyCantBeUsedBoth');
		setEventMessages($mesg, array(), 'errors');
		$_POST['PROPALEHISTORY_AUTO_ARCHIVE'] = 0;
	}
	if (dolibarr_set_const($db, $code, GETPOST($code), 'chaine', 0, '', $conf->entity) > 0)
	{
		if ($code == 'PROPALEHISTORY_ARCHIVE_ON_MODIFY') {
			dolibarr_set_const($db, 'PROPALEHISTORY_AUTO_ARCHIVE', 0, 'chaine', 0, '', $conf->entity);
		}
		header("Location: ".$_SERVER["PHP_SELF"]);
		exit;
	}
	else
	{
		dol_print_error($db);
	}
}
	
if (preg_match('/del_(.*)/',$action,$reg))
{
	$code=$reg[1];
	if (dolibarr_del_const($db, $code, 0) > 0)
	{
		Header("Location: ".$_SERVER["PHP_SELF"]);
		exit;
	}
	else
	{
		dol_print_error($db);
	}
}

/*
 * View
 */
$page_name = "PropalHistory";
llxHeader('', $langs->trans($page_name));

// Subheader
$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">'
    . $langs->trans("BackToModuleList") . '</a>';
print_fiche_titre($langs->trans($page_name), $linkback);

// Configuration header
/*$head = doc2projectAdminPrepareHead();
dol_fiche_head(
    $head,
    'settings',
    $langs->trans("Module1040900Name"),
    0,
    "project"
);*/

$ok = $conf->propal->enabled;

if($ok) {
	// Setup page goes here
	$form=new Form($db);
	$var=false;
	print '<table class="noborder" width="100%">';
	print '<tr class="liste_titre">';
	print '<td>'.$langs->trans("Parameters").'</td>'."\n";
	print '<td align="center" width="20">&nbsp;</td>';
	print '<td align="center" width="100">'.$langs->trans("Value").'</td>'."\n";
	
	// Display convert button on proposal
	$var=!$var;
	print '<tr '.$bc[$var].'>';
	print '<td>'.$langs->trans("AutoArchive").'</td>';
	print '<td align="center" width="20">&nbsp;</td>';
	print '<td align="right" width="300">';
	print '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print '<input type="hidden" name="action" value="set_PROPALEHISTORY_AUTO_ARCHIVE">';
	print $form->selectyesno("PROPALEHISTORY_AUTO_ARCHIVE",$conf->global->PROPALEHISTORY_AUTO_ARCHIVE,1);
	print '<input type="submit" class="button" value="'.$langs->trans("Modify").'">';
	print '</form>';
	print '</td></tr>';

	$var=!$var;
	print '<tr '.$bc[$var].'>';
	print '<td>'.$langs->trans("PROPALEHISTORY_SHOW_VERSION_PDF").'</td>';
	print '<td align="center" width="20">&nbsp;</td>';
	print '<td align="right" width="300">';
	print '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print '<input type="hidden" name="action" value="set_PROPALEHISTORY_SHOW_VERSION_PDF">';
	print $form->selectyesno("PROPALEHISTORY_SHOW_VERSION_PDF",$conf->global->PROPALEHISTORY_SHOW_VERSION_PDF,1);
	print '<input type="submit" class="button" value="'.$langs->trans("Modify").'">';
	print '</form>';
	print '</td></tr>';
	
	$var=!$var;
	print '<tr '.$bc[$var].'>';
	print '<td>'.$langs->trans("PROPALEHISTORY_HIDE_VERSION_ON_TABS").'</td>';
	print '<td align="center" width="20">&nbsp;</td>';
	print '<td align="right" width="300">';
	print '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print '<input type="hidden" name="action" value="set_PROPALEHISTORY_HIDE_VERSION_ON_TABS">';
	print $form->selectyesno("PROPALEHISTORY_HIDE_VERSION_ON_TABS",$conf->global->PROPALEHISTORY_HIDE_VERSION_ON_TABS,1);
	print '<input type="submit" class="button" value="'.$langs->trans("Modify").'">';
	print '</form>';
	print '</td></tr>';

	$var=!$var;
	print '<tr '.$bc[$var].'>';
	print '<td>'.$langs->trans("PROPALEHISTORY_ARCHIVE_PDF_TOO").'</td>';
	print '<td align="center" width="20">&nbsp;</td>';
	print '<td align="right" width="300">';
	print '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print '<input type="hidden" name="action" value="set_PROPALEHISTORY_ARCHIVE_PDF_TOO">';
	print $form->selectyesno("PROPALEHISTORY_ARCHIVE_PDF_TOO",$conf->global->PROPALEHISTORY_ARCHIVE_PDF_TOO,1);
	print '<input type="submit" class="button" value="'.$langs->trans("Modify").'">';
	print '</form>';
	print '</td></tr>';
	
	$var=!$var;
	print '<tr '.$bc[$var].'>';
	print '<td>'.$langs->trans("PROPALEHISTORY_ARCHIVE_ON_MODIFY").'</td>';
	print '<td align="center" width="20">&nbsp;</td>';
	print '<td align="right" width="300">';
	print '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print '<input type="hidden" name="action" value="set_PROPALEHISTORY_ARCHIVE_ON_MODIFY">';
	print $form->selectyesno("PROPALEHISTORY_ARCHIVE_ON_MODIFY",$conf->global->PROPALEHISTORY_ARCHIVE_ON_MODIFY,1);
	print '<input type="submit" class="button" value="'.$langs->trans("Modify").'">';
	print '</form>';
	print '</td></tr>';
	
	
} else {
	print $langs->trans('ModuleNeedProposalOrOrderModule');
}

print '</table>';

llxFooter();

$db->close();