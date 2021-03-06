<?php
// Copyright 2011 Toby Zerner, Simon Zerner
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

/**
 * MRM4Ever skin file.
 * 
 * @package esoTalk
 */

ET::$skinInfo["MRM4Ever"] = array(
	"name" => "MRM4Ever",
	"description" => "Mundo RPG Maker 4 Ever",
	"version" => ESOTALK_VERSION,
	"author" => "Artur Hansen & Gabriel Teles",
	"authorEmail" => "kyo-panda@hotmail.com; gab.teles@hotmail.com",
	"authorURL" => "http://www.mundorpgmaker.com",
	"license" => "GPLv2"
);

class ETSkin_MRM4Ever extends ETSkin {


/**
 * Initialize the skin.
 * 
 * @param ETController $sender The page controller.
 * @return void
 */
public function handler_init($sender)
{
	$sender->addCSSFile($this->getResource("base.css"), true);
	$sender->addCSSFile($this->getResource("styles.css"), true);

	// If we're viewing from a mobile browser, add the mobile CSS and change the master view.
	if (isMobileBrowser()) {
		$sender->addCSSFile($this->getResource("mobile.css"), true);
		$sender->masterView = "mobile.master";
		$sender->addToHead("<meta name='viewport' content='width=device-width; initial-scale=1.0; maximum-scale=1.0;'>");
	} else {
		$sender->masterView = "theme.master";
	}

	// If custom colors have been set in this skin's settings, add some CSS to the page.
	$styles = array();

	// If a custom header color has been set...
	if ($c = C("skin.MRM4Ever.headerColor")) {
		$styles[] = "#hdr, #navigationMenu {background-color:$c}";

		// If the header color is in the top half of the lightness spectrum, add the "lightHdr" class to the body.
		$rgb = colorUnpack($c, true);
		$hsl = rgb2hsl($rgb);
		if ($hsl[2] >= 0.5) $sender->bodyClass .= " lightHdr";
	}

	// If a custom body color has been set...
	if ($c = C("skin.MRM4Ever.bodyColor")) {
		$styles[] = "body, .scrubberMore {background-color:$c !important}";

		// If the body color is in the bottom half of the lightness spectrum, add the "darkBody" class to the body.
		$rgb = colorUnpack($c, true);
		$hsl = rgb2hsl($rgb);
		if ($hsl[2] < 0.5) $sender->bodyClass .= " darkBody";

		// Slightly darken the body color and set it as the border color for the body content area.
		$hsl[2] = max(0, $hsl[2] - 0.1);
		$hsl[1] = min($hsl[1], 0.5);
		$b = colorPack(hsl2rgb($hsl), true);
		$styles[] = "#body-content, #navigationMenu {border-color:$b}";
	}

	// Menu de op��es 2
	$menu = ETFactory::make("menu");
	//$menu->add($id, $html, $position);
	$labels = C("skin.MRM4Ever.menuLabel");
	$urls   = C("skin.MRM4Ever.menuURL");
	foreach ($labels as $key => $label){
		$url = $urls[$key];
		$menu->add($label, "<a href='$url' class='link-navMenu'>" . $label . "</a>");
	}
	$sender->data("navMenu", $menu->getContents());
	
	// Logo Image
	// TODO: Verificar data e atualizar logo automaticamente para datas comemorativas
	$sender->data("logoURL", $this->getResource('logo.png'));
	
	// If a custom body background image has been set...
	if ($img = C("skin.MRM4Ever.bodyImage"))
		$styles[] = "body {background-image:url(".getWebPath($img)."); background-position:top center}";
	
	// Do we want this background image to not repeat?
	if ($img and C("skin.MRM4Ever.noRepeat"))
		$styles[] = "body {background-repeat:no-repeat}";
    
  // If a custom body background image has been set...
	if ($img = C("skin.MRM4Ever.bodyImage"))
		$styles[] = "body {background-image:url(".getWebPath($img)."); background-position:top center}";
    
  // Altura e largura do avatar
  $ava_width = C("skin.MRM4Ever.avatarWidth");
  $ava_height = C("skin.MRM4Ever.avatarHeight");
  if ($ava_width && $ava_height) {
    $styles[] = "div.avatar { height: " . $ava_height . "px; width: " . $ava_width . "px;}
                 img.avatar { max-height: " . $ava_height . "px; max-width: " . $ava_width . "px; overflow: auto; }
                 .post {padding-left:" . ($ava_width + 14) . "px;}
                 .poster {margin-left:-" . ($ava_width + 14) . "px;}";
  }

	// If we have any custom styles at all, add them to the page head.
	if (count($styles)) $sender->addToHead("<style type='text/css'>\n".implode("\n", $styles)."\n</style>");
}


/**
 * Construct and process the settings form for this skin, and return the path to the view that should be 
 * rendered.
 * 
 * @param ETController $sender The page controller.
 * @return string The path to the settings view to render.
 */
public function settings($sender)
{
	// Set up the settings form.
	$form = ETFactory::make("form");
	$form->action = URL("admin/appearance");
	$form->setValue("headerColor", C("skin.MRM4Ever.headerColor"));
	$form->setValue("bodyColor", C("skin.MRM4Ever.bodyColor"));
  $form->setValue("avatarWidth", C("skin.MRM4Ever.avatarWidth"));
  $form->setValue("avatarHeight", C("skin.MRM4Ever.avatarHeight"));
	$form->setValue("noRepeat", (bool)C("skin.MRM4Ever.noRepeat"));
	$form->setValue("bodyImage", (bool)C("skin.MRM4Ever.bodyImage"));
	$form->setValue("menuLabel", C("skin.MRM4Ever.menuLabel"));
	$form->setValue("menuURL",  C("skin.MRM4Ever.menuURL"));

	// If the form was submitted...
	if ($form->validPostBack("save")) {		
		// Construct an array of config options to write.
		$config = array();
		$config["skin.MRM4Ever.headerColor"] = $form->getValue("headerColor");
		$config["skin.MRM4Ever.bodyColor"] = $form->getValue("bodyColor");
    
    // Pegar largura e altura do avatar
    $ava_width = $form->getValue("avatarWidth");
    $ava_height = $form->getValue("avatarHeight");
    
    // Validar altura e largura do avatar
    if (!is_numeric($ava_width)) {
      $form->error("avatarWidth", "A largura do avatar deve ser um valor num&eacute;rico!");
    }
    if (!is_numeric($ava_height)) {
      $form->error("avatarHeight", "A altura do avatar deve ser um valor num&eacute;rico!");
    }
    if (is_numeric($ava_width) && is_numeric($ava_height)) {
      $config["skin.MRM4Ever.avatarWidth"] = $ava_width;
      $config["skin.MRM4Ever.avatarHeight"] = $ava_height;
    }
		
		// Upload a body bg image if necessary.
		if ($form->getValue("bodyImage") and !empty($_FILES["bodyImageFile"]["tmp_name"])) 
			$config["skin.MRM4Ever.bodyImage"] = $this->uploadBackgroundImage($form);
		elseif (!$form->getValue("bodyImage")) $config["skin.MRM4Ever.bodyImage"] = false;
			$config["skin.MRM4Ever.noRepeat"] = (bool)$form->getValue("noRepeat");

		// Processa itens do menu
		$config["skin.MRM4Ever.menuLabel"] = array();
		$config["skin.MRM4Ever.menuURL"]   = array();
		
		$labels = $form->getValue("menuLabel");
		$urls   = $form->getValue("menuURL");
		
		foreach($labels as $key => $label)
			if (!empty($label)){
				$config["skin.MRM4Ever.menuLabel"][] = $label;
				$config["skin.MRM4Ever.menuURL"][]   = $urls[$key];
			}
			
		if (!$form->errorCount()) {

			// Write the config file.
			ET::writeConfig($config);

			$sender->message(T("message.changesSaved"), "success");
			$sender->redirect(URL("admin/appearance"));

		}
	}

	$sender->data("skinSettingsForm", $form);
	$sender->addCSSFile("js/lib/farbtastic/farbtastic.css");
	$sender->addJSFile("js/lib/farbtastic/farbtastic.js");
	return $this->getView("settings.admin");
}


/**
 * Upload a background image.
 * 
 * @return void
 */
protected function uploadBackgroundImage($form)
{
	$uploader = ET::uploader();

	try {

		// Validate and get the uploaded file from this field.
		$file = $uploader->getUploadedFile("bodyImageFile");

		// Save it as an image, restricting it to a maximum size.
		$bg = $uploader->saveAsImage($file, PATH_UPLOADS."/bg", 1, 1, "min");
		$bg = str_replace(PATH_UPLOADS, "uploads", $bg);

		// Delete the old background image (if we didn't just overwrite it.)
		if ($bg != C("skin.MRM4Ever.bodyImage")) @unlink(C("skin.MRM4Ever.bodyImage"));

		return $bg;

	} catch (Exception $e) {

		// If something went wrong up there, add the error message to the form.
		$form->error("bodyImageFile", $e->getMessage());

	}
}

}

?>