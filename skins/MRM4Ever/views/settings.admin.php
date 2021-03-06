<?php
// Copyright 2011 Toby Zerner, Simon Zerner
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

/**
 * Displays the settings form for the MRM4Ever skin.
 *
 * @package esoTalk
 */

$form = $data["skinSettingsForm"];
?>

<?php echo $form->open(); ?>

<ul class='form'>

<li class='sep'></li>

<h3>Aparência do fórum</h3>

<li class='sep'></li>

<li id='headerColor'>
<label>Cor do cabeçalho</label>
<?php echo $form->input("headerColor", "text", array("class" => "color")); ?> <a href='#' class='reset'>Resetar</a>
</li>


<li id='bodyColor'>
<label>Cor de fundo</label>
<?php echo $form->input("bodyColor", "text", array("class" => "color")); ?> <a href='#' class='reset'>Resetar</a>
</li>

<li class='sep'></li>

<li id='bodyImage'>
<label>Imagem de Fundo</label>
<div class='checkboxGroup'>
<label class='checkbox'><?php echo $form->checkbox("bodyImage"); ?> Usar imagem de fundo</label>
<div class='indent'>
<?php echo $form->input("bodyImageFile", "file", array("class" => "bodyImageFile text")); ?>
<label class='checkbox'><?php echo $form->checkbox("noRepeat"); ?> Não repetir</label>
</div>
</div>
</li>

<li class='sep'></li>

<li id="menuItems">
	<label>Itens do menu</label>
	<ul id="menuFields" style="list-style-type:decimal">
		<?php 
			$label = $form->getValue("menuLabel");
			$url = $form->getValue("menuURL");
			$fieldCount = count($label);
			for ($i = 0; $i < $fieldCount; $i++):
		?>
			<li>
				<label class="inline">Texto</label> <?php echo $form->input("menuLabel[]", "text", array("class" => "menuLabel", "value" => $label[$i])); ?>
				<label class="inline">URL</label> <?php echo $form->input("menuURL[]", "text", array("class" => "menuURL", "value" => $url[$i])); ?>
				<label class="inline"><a href="javascript:void(0)" class="RemoveMenuField">[Remover]</a></label>
			</li>
		<?php
			endfor;
		?>
	</ul>
	<ul>
		<a href="javascript:void(0)" id="AddMenuField">[Adicionar Campo]</a>
	</ul>
</li>

<li class='sep'></li>

<h3>Configurações adicionais</h3>

<li class='sep'></li>

<li id='avatarWidth'>
<label>Largura do Avatar</label>
<?php echo $form->input("avatarWidth", "text", array("class" => "number")); ?>
</li>

<li id='avatarHeight'>
<label>Altura do Avatar</label>
<?php echo $form->input("avatarHeight", "text", array("class" => "number")); ?>
</li>

<li class='sep'></li>

<li><?php echo $form->saveButton(); ?></li>
</ul>

<?php echo $form->close(); ?>

<script>
$(function() {

	// Turn a normal text input into a color picker, and run a callback when the color is changed.
	function colorPicker(id, callback) {

		// Create the color picker container.
		var picker = $("<div id='"+id+"-colorPicker'></div>").appendTo("body").addClass("popup").hide();

		// When the input is focussed upon, show the color picker.
		$("#"+id+" input").focus(function() {
			picker.css({position: "absolute", top: $(this).offset().top - picker.outerHeight(), left: $(this).offset().left}).show();
		})

		// When focus is lost, hide the color picker.
		.blur(function() {
			picker.hide();
		})

		// Add a color swatch before the input.
		.before("<span class='colorSwatch'></span>");

		// Create a handler function for when the color is changed to update the input and swatch, and call
		// the custom callback function.
		var handler = function(color) {
			callback(color, picker);
			$("#"+id+" input").val(color.toUpperCase());
			$("#"+id+" .colorSwatch").css("backgroundColor", color);
			$("#"+id+" .reset").toggle(!!color);
		}

		// Set up a farbtastic instance inside the picker we've created.
		$.farbtastic(picker, function(color) {
			handler(color);
		}).setColor($("#"+id+" input").val());

		// When the "reset" link is clicked, reset the color.
		$("#"+id+" .reset").click(function(e) {
			e.preventDefault();
			handler("");
		}).toggle(!!$("#"+id+" input").val());

	}

	// Turn the "header color" field into a color picker.
	colorPicker("headerColor", function(color, picker) {

		// If no color is selected, use the default one.
		color = color ? color : "#333333";

		// Change the header's background color.
		$("#hdr, #navigationMenu").css("backgroundColor", color);

		// Unpack this color and convert it to HSL. If the lightness is > 0.5, set the "lightHdr" class on the body.
		var rgb = $.farbtastic(picker).unpack(color);
		var hsl = $.farbtastic(picker).RGBToHSL(rgb);
		$("body").toggleClass("lightHdr", hsl[2] > 0.5);

	});

	// Turn the "body color" field into a color picker.
	colorPicker("bodyColor", function(color, picker) {

		// If no color is selected, use the default one.
		color = color ? color : "#f4f4f4";

		// Change the body's background color.
		$("body").attr("style", "background-color:"+color+" !important");

		// Unpack this color and convert it to HSL. If the lightness is < 0.5, set the "darkBody" class on the body.
		var rgb = $.farbtastic(picker).unpack(color);
		var hsl = $.farbtastic(picker).RGBToHSL(rgb);
		$("body").toggleClass("darkBody", hsl[2] < 0.5);

		// Slightly darken the body color and set it as the border color for the body content area.
		hsl[2] = Math.max(0, hsl[2] - 0.1);
		hsl[1] = Math.min(hsl[1], 0.5);
		var b = $.farbtastic(picker).pack($.farbtastic(picker).HSLToRGB(hsl));
		$("#body-content, #navigationMenu").css("borderColor", b);

	});

	// Add handlers to the background image checkbox.
	$("#bodyImage input[name=bodyImage]").change(function(e) {
		$("#bodyImage .indent").toggle($(this).prop("checked"));
		if (!$(this).prop("checked")) {
			$("body").css("backgroundImage", "none");
		}
	}).change();

	function removeElem(e) {
		var elem   = e.target.parentNode.parentNode
		elem.parentNode.removeChild(elem);
	}

	$("#AddMenuField").click(function() {
		var menuLabel = $("<?php echo $form->input("menuLabel[]", "text", array("class" => "menuLabel")); ?>");
		var menuURL   = $("<?php echo $form->input("menuURL[]",   "text", array("class" => "menuURL"  )); ?>");
		
		link = $("<a href='javascript:void(0)' class='RemoveMenuField'>[Remover]</a>");
		
		$("#menuFields").append(
			$("<li>").append(
				$("<label class='inline'>Texto&nbsp;</label> ")
			).append(
				menuLabel
			).append(
				$("<label class='inline'>&nbspURL&nbsp</label>")
			).append(
				menuURL
			).append(
				$("<label class='inline'>&nbsp</label>").append(
					link
				)
			)
		);
		
		link.click(removeElem);
	});
	
	$(".RemoveMenuField").click(removeElem);
});
</script>