<?php

/**
 * SMF - YOURLS Integration
 *
 * @package SMF
 * @author Suki <suki@missallsunday.com>
 * @copyright 2013 Jessica Gonz&aacute;lez
 * @license http://www.mozilla.org/MPL/ MPL 2.0
 *
 * @version 1.0
 */

/*
 * Version: MPL 2.0
 *
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file,
 * You can obtain one at http://mozilla.org/MPL/2.0/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 */

global $scripturl, $txt;

$txt['Yourls_title_main'] = 'YOURLS';
$txt['Yourls_admin_title'] = 'Yourls panel de administraci&oacute;n';
$txt['Yourls_settingsEnableBBC'] = 'Activar el bot&oacute;n BBC';
$txt['Yourls_settingsEnableBBC_sub'] = 'Marca esta opci&oacute;n si deseas usar el bot&oacute;n BBC para acortar cualquier direcci&oacute;n';
$txt['Yourls_settingsPass'] = 'Contrase&ntilde;a de usuario';
$txt['Yourls_settingsPass_sub'] = 'La contrase&ntilde;a para connectar con el servidor, este campo es obligatorio para el funcionamiento del mod.';
$txt['Yourls_settingsUser'] = 'Usuario';
$txt['Yourls_settingsUser_sub'] = 'El usuario para conectar con el servidor, este campo es obligatorio para el funcionamiento del mod.';
$txt['Yourls_settingsDomain'] = 'Dominio del script yourls';
$txt['Yourls_settingsDomain_sub'] = 'El dominio donde se encuentra tu instalaci&oacute;n yourls';
$txt['Yourls_settingsEnable'] = 'Activar la integraci&oacute;n con yourls';
$txt['Yourls_settingsEnable_sub'] = 'Con esta opci&oacute;n activas la conversi&oacute;n de cualquier tema en un link m&aacute;s corto usando tu script yourls';
$txt['Yourls_settingsDesc'] = 'Desde aqu&iacute; puedes cambiar cualquier par&aacute;metro del mod, el mod revisa el dominio yourls cada 2 minutos para asegurarse de que el dominio est&aacute; activo, de lo contrario, el mod se desactivar&aacute; autom&aacute;ticamente y rear&aacute; un error en el log de errores para informar a el administrador.';
$txt['Yourls_shortUrl'] = 'Url corta ';
$txt['Yourls_shortUrlForTopic'] = 'URL corta para este tema:';
$txt['Yourls_BBCtag'] = 'El tag para el BBC';
$txt['Yourls_BBCtag_sub'] = 'Ten cuidado y no cambies muy seguido esta opci&oacute;n o puedes terminar con BBC rotos.';
$txt['Yourls_bbcDesc'] = 'Usa este BBC para crear una url m&aacute;s corta de cualquier direcci&oacute;n v&aacute;lida.';
$txt['Yourls_settingsIconSize'] = 'El tama&ntilde;o de los &iacute;conos';
$txt['Yourls_settingsIconSize_sub'] = 'Hay cuatro tama&ntilde;os predefinidos, todos los &iacute;conos tendrán el mismo tama&ntilde;o.';
$txt['Yourls_settingsIcon16'] = '16';
$txt['Yourls_settingsIcon24'] = '24';
$txt['Yourls_settingsIcon32'] = '32';
$txt['Yourls_settingsIcon48'] = '48';

// Error strings
$txt['Yourls_error_noUrl'] = 'Ninguna direcci&oacute;n fu&eacute; provista';
$txt['Yourls_error_emptyDomain'] = 'La configuraci&oacute;n dle dominio YOURLS est&aacute; vacia, tienes que tener una instalaci&oacute;n del script yourls funcionando correctamente antes de usar este mod.';
$txt['Yourls_error_dataFetchFailed'] = 'Hubo un problema al tratar de conseguir la informaci&oacute;n desde el servidor remoto.';
$txt['Yourls_error_noValidInfoAction'] = 'Debes de mandar una petici&oacute;n v&aacute;lida';
$txt['Yourls_error_server_error'] = 'El servidor remoto no est&aacute; respondiendo, para prevenir cualquier da&ntilde;o a tu foro, el mod se ha desactivado. Por favor revisa manualmente que el servidor yourls est&eacute; activo y activa de nuevo el mod.';

