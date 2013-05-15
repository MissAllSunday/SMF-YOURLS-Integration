<?php

/**
 * SMF - YOURLS Integration
 *
 * @package SMF
 * @author Suki <suki@missallsunday.com>
 * @copyright 2013 Jessica González
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
$txt['Yourls_admin_title'] = 'Yourls panel de administración';
$txt['Yourls_settingsEnableBBC'] = 'Activar el botón BBC';
$txt['Yourls_settingsEnableBBC_sub'] = 'Marca esta opción si deseas usar el botón BBC para acortar cualquier dirección';
$txt['Yourls_settingsPass'] = 'Contraseña de usuario';
$txt['Yourls_settingsPass_sub'] = 'La contraseña para connectar con el servidor, este campo es obligatorio para el funcionamiento del mod.';
$txt['Yourls_settingsUser'] = 'Usuario';
$txt['Yourls_settingsUser_sub'] = 'El usuario para conectar con el servidor, este campo es obligatorio para el funcionamiento del mod.';
$txt['Yourls_settingsDomain'] = 'Dominio del script yourls';
$txt['Yourls_settingsDomain_sub'] = 'El dominio donde se encuentra tu instalación yourls';
$txt['Yourls_settingsEnable'] = 'Activar la integración con yourls';
$txt['Yourls_settingsEnable_sub'] = 'Con esta opción activas la conversión de cualquier tema en un link más corto usando tu script yourls';
$txt['Yourls_settingsDesc'] = 'Desde aquí puedes cambiar cualquier parámetro del mod, el mod revisa el dominio yourls cada 2 minutos para asegurarse de que el dominio está activo, de lo contrario, el mod se desactivará automáticamente y reará un error en el log de errores para informar a el administrador.';
$txt['Yourls_shortUrl'] = 'Url corta ';
$txt['Yourls_shortUrlForTopic'] = 'URL corta para este tema:';
$txt['Yourls_BBCtag'] = 'El tag para el BBC';
$txt['Yourls_BBCtag_sub'] = 'Ten cuidado y no cambies muy seguido esta opción o puedes terminar con BBC rotos.';
$txt['Yourls_bbcDesc'] = 'Usa este BBC para crear una url más corta de cualquier dirección válida.';
$txt['Yourls_settingsIconSize'] = 'El tamaño de los íconos';
$txt['Yourls_settingsIconSize_sub'] = 'Hay cuatro tamaños predefinidos, todos los íconos tendrán el mismo tamaño.';
$txt['Yourls_settingsIcon16'] = '16';
$txt['Yourls_settingsIcon24'] = '24';
$txt['Yourls_settingsIcon32'] = '32';
$txt['Yourls_settingsIcon48'] = '48';
$txt['Yourls_shareShortUrl'] = 'Comparte este tema';
$txt['Yourls_shareOn_facebook'] = 'Activar share on facebook';
$txt['Yourls_shareOn_linkedin'] = 'Activar share on linkedin';
$txt['Yourls_shareOn_twitter'] = 'Activarshare on twitter';
$txt['Yourls_shareOn_google'] = 'Activar share on google';
$txt['Yourls_shareOn'] = 'Comparte en '; 

// Error strings
$txt['Yourls_error_noUrl'] = 'Ninguna dirección fué provista';
$txt['Yourls_error_emptyDomain'] = 'La configuración dle dominio YOURLS está vacia, tienes que tener una instalación del script yourls funcionando correctamente antes de usar este mod.';
$txt['Yourls_error_dataFetchFailed'] = 'Hubo un problema al tratar de conseguir la información desde el servidor remoto.';
$txt['Yourls_error_noValidInfoAction'] = 'Debes de mandar una petición válida';
$txt['Yourls_error_server_error'] = 'El servidor remoto no está respondiendo, para prevenir cualquier daño a tu foro, el mod se ha desactivado. Por favor revisa manualmente que el servidor yourls esté activo y activa de nuevo el mod.';

