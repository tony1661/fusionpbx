<?php

	//application details
		$apps[$x]['name'] = "Recordings";
		$apps[$x]['uuid'] = "83913217-c7a2-9e90-925d-a866eb40b60e";
		$apps[$x]['category'] = "Switch";;
		$apps[$x]['subcategory'] = "";
		$apps[$x]['version'] = "1.1";
		$apps[$x]['license'] = "Mozilla Public License 1.1";
		$apps[$x]['url'] = "http://www.fusionpbx.com";
		$apps[$x]['description']['en-us'] = "Manage recordings primarily used with an IVR.";
		$apps[$x]['description']['en-gb'] = "Manage recordings primarily used with an IVR.";
		$apps[$x]['description']['ar-eg'] = "";
		$apps[$x]['description']['de-at'] = "Aufnahmen verwalten. Diese werden hauptsächlich für Anrufzentralen (IVR) verwendet.";
		$apps[$x]['description']['de-ch'] = "";
		$apps[$x]['description']['de-de'] = "Aufnahmen verwalten. Diese werden hauptsächlich für Anrufzentralen (IVR) verwendet.";
		$apps[$x]['description']['es-cl'] = "Administrador de grabaciones, utilizadas primordialmente con un IVR";
		$apps[$x]['description']['es-mx'] = "";
		$apps[$x]['description']['fr-ca'] = "Gestion des guides vocaux principalement utilisés dans les RVI";
		$apps[$x]['description']['fr-fr'] = "Gestion des guides vocaux principalement utilisés dans les RVI";
		$apps[$x]['description']['he-il'] = "";
		$apps[$x]['description']['it-it'] = "";
		$apps[$x]['description']['nl-nl'] = "Beheer opnamen met name gebruikt in een IVR.";
		$apps[$x]['description']['pl-pl'] = "";
		$apps[$x]['description']['pt-br'] = "";
		$apps[$x]['description']['pt-pt'] = "Gestor de gravações utilizadas principalmente com um IVR.";
		$apps[$x]['description']['ro-ro'] = "";
		$apps[$x]['description']['ru-ru'] = "";
		$apps[$x]['description']['sv-se'] = "";
		$apps[$x]['description']['uk-ua'] = "";

	//destination details
		$y=0;
		$apps[$x]['destinations'][$y]['type'] = "sql";
		$apps[$x]['destinations'][$y]['label'] = "recordings";
		$apps[$x]['destinations'][$y]['name'] = "recordings";
		$apps[$x]['destinations'][$y]['sql'] = "select recording_uuid as uuid, recording_name as name, recording_filename as destination, recording_description as description from v_recordings";
		$apps[$x]['destinations'][$y]['where'] = "where domain_uuid = '\${domain_uuid}' ";
		$apps[$x]['destinations'][$y]['order_by'] = "recording_name asc";
		$apps[$x]['destinations'][$y]['field']['uuid'] = "recording_uuid";
		$apps[$x]['destinations'][$y]['field']['name'] = "recording_name";
		$apps[$x]['destinations'][$y]['field']['destination'] = "recording_filename";
		$apps[$x]['destinations'][$y]['field']['description'] = "recording_description";
		$apps[$x]['destinations'][$y]['select_value']['dialplan'] = "lua:streamfile.lua \${destination}";
		$apps[$x]['destinations'][$y]['select_value']['ivr'] = "menu-exec-app:lua streamfile.lua \${destination}";
		$apps[$x]['destinations'][$y]['select_label'] = "\${name}";

	//permission details
		$y=0;
		$apps[$x]['permissions'][$y]['name'] = "recording_view";
		$apps[$x]['permissions'][$y]['menu']['uuid'] = "e4290fd2-3ccc-a758-1714-660d38453104";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "recording_add";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "recording_edit";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "recording_delete";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "recording_all";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "recording_upload";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "recording_play";
		$apps[$x]['permissions'][$y]['groups'][] = "user";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "recording_download";
		$apps[$x]['permissions'][$y]['groups'][] = "user";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "recording_destinations";
		$apps[$x]['permissions'][$y]['groups'][] = "user";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "recording_password";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";

	//default settings
		$y=0;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "a780b88f-188b-4d73-a1df-f93a114ca16d";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "recordings";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "storage_type";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "base64";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "960828e1-8d6b-4381-86c4-fa03fce4276a";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "recordings";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "recording_max_length";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "300";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Maximum length of a recording (in seconds).";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "a1e837c6-e5bf-460f-aa7e-9b35d53d015a";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "recordings";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "recording_silence_threshold";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "200";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Energy level below this number is considered silence.";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "4db91322-83c1-4c6a-8966-f8db0b84cdcd";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "recordings";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "recording_silence_seconds";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "3";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Seconds of silence to end the recording for the voicemail message.";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "e4b509cc-1622-4867-b8b8-c48585c4d796";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "recordings";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "recording_prefix";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "recording_";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Set the prefix text for the recording name and filename (overrides recordings dialplan).";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "f74ab51f-e7c4-423e-b5b6-6f806ecf96b4";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "recordings";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "recording_password";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "numeric";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = generate_password(!empty($_SESSION['voicemail']['password_length']['numeric']) && is_numeric($_SESSION['voicemail']['password_length']['numeric']) ? $_SESSION['voicemail']['password_length']['numeric'] : 8, 1);
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Set the password required to create a recording (overrides recordings dialplan).";

	//schema details
		$y=0;
		$apps[$x]['db'][$y]['table']['name'] = "v_recordings";
		$apps[$x]['db'][$y]['table']['parent'] = "";
		$z=0;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "recording_uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
		$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "primary";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "domain_uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
		$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "foreign";
		$apps[$x]['db'][$y]['fields'][$z]['key']['reference']['table'] = "v_domains";
		$apps[$x]['db'][$y]['fields'][$z]['key']['reference']['field'] = "domain_uuid";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "v_id";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$apps[$x]['db'][$y]['fields'][$z]['deprecated'] = "true";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name']['text'] = "recording_filename";
		$apps[$x]['db'][$y]['fields'][$z]['name']['deprecated'] = "filename";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['search'] = 'true';
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name']['text'] = "recording_name";
		$apps[$x]['db'][$y]['fields'][$z]['name']['deprecated'] = "recordingname";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['search'] = 'true';
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "recording_voice";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['search'] = 'true';
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "recording_message";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['search'] = 'true';
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name']['text'] = "recording_description";
		$apps[$x]['db'][$y]['fields'][$z]['name']['deprecated'] = "descr";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['search'] = 'true';
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "recording_base64";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "Recording file encoded in base64.";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "insert_date";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'timestamptz';
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'date';
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'date';
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "insert_user";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "update_date";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'timestamptz';
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'date';
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'date';
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "update_user";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";

?>
