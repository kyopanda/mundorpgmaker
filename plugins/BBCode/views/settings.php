<?php
// Copyright 2012+ Gabriel Teles
// Esse arquivo é um adicional à configuração do plugin BBCode

if (!defined("IN_ESOTALK")) exit;

/**
 * Displays the settings form for the BBCode Plugin.
 *
 * @package bbcode
 */
$form = $data["pluginSettingsForm"];
?>

<ul class='form'>

	<li class='sep'></li>
	<label>Lista de BBCodes</label>
	
        <?php echo $form->open(); ?>
            <ol id="definedBBCodes">
                    <?php
                            if (count($data["bbcodes"])):
                                foreach ($data["bbcodes"] as $bbcode => $tag)
                                    echo $form->button("modifyBBCQuery", $bbcode, array("class" => "button submit"));
                            else:
                    ?>
                            Não existem BBCodes definidos
                    <?php
                            endif;
                    ?>
            </ol>
       <?php echo $form->close(); ?>
	
	<li class='sep'></li>
	
	<label>Novo BBCode</label>
	<div id='bbcSetNavigation' style='margin-bottom: 10px'>
		<a href="javascript: void(0)" class="button" id="simple">Tag Simples</a>
		<a href="javascript: void(0)" class="button" id="enhanced">Tag Aprimorada</a>
		<a href="javascript: void(0)" class="button" id="callback">Tag Callback</a>
	</div>
	<div id='bbcForms'>
		<?php echo $form->open(); ?>
			<?php echo $form->input("tagType", "hidden", array('value' => 0)); ?>
			<fieldset class='simple area' style="display: block">
				<label>Nome da Tag</label>
				<?php echo $form->input("tagName", "text", array('value' => '')); ?>
					
				<label>Substituição Inicial</label>
				<?php echo $form->input("tagStart", "text", array('value' => '')); ?>
					
				<label>Substituição Final</label>
				<?php echo $form->input("tagEnd", "text", array('value' => '')); ?>
				
				<label>Tag complexa?</label>
				<?php echo $form->input("tagComplex", "checkbox", array('class' => 'text', 'value' => '')); ?>
				
				<li class='sep'></li>
				<?php echo $form->button("createBBC", T("Create BBCode"), array("class" => "big submit")); ?>
			</fieldset>
		<?php echo $form->close(); ?>
		<!-------------------------------------------->
		<?php echo $form->open(); ?>
			<?php echo $form->input("tagType", "hidden", array('value' => 1)); ?>
			<fieldset class='enhanced area'>
				<label>Nome da Tag</label>
				<?php echo $form->input("tagName", "text", array('value' => '')); ?>
				<label>Template</label>
				<?php echo $form->input("tagTemplate", "text", array('value' => '')); ?>
				<label>Tag complexa?</label>
				<?php echo $form->input("tagComplex", "checkbox", array('class' => 'text')); ?>
				<li class='sep'></li>
				<label>Atributos</label>
				<ol id="AttrList">
					<li>
						<label class='inline'>Nome</label>
						<?php echo $form->input("tagAttrName[]", "text", array("class" => "smallText", 'value' => '')); ?>
						<label class='inline'>Validação</label>
						<?php echo $form->input("tagAttrRgx[]", "text", array("class" => "smallText", 'value' => '')); ?>
						<label class='inline'><a href="javascript:void(0)" class="RemoveAttr">[Remover]</a></label>
					</li>
				</ol>
				<a href="javascript:void(0)" id="AddAttr">[Adicionar Atributo]</a>
				<li class='sep'></li>
				<?php echo $form->button("createBBC", T("Create BBCode"), array("class" => "big submit")); ?>
			</fieldset>
		<?php echo $form->close(); ?>
		<!-------------------------------------------->
		<?php echo $form->open(); ?>
			<?php echo $form->input("tagType", "hidden", array('value' => 2)); ?>
			<fieldset class='callback area'>
				<label>Nome da Tag</label>
				<?php echo $form->input("tagName", "text", array('value' => '')); ?>
				<label>Tag complexa?</label>
				<?php echo $form->input("tagComplex", "checkbox", array('class' => 'text')); ?>
				<li class='sep'></li>
				<label style='width:100%'>Função de validação</label>
				<span class='desc'><strong>Argumentos:</strong> $bbcode, $action, $name, $default, $params, $content</span>
				<?php echo $form->input("tagFunction", "textarea", array('id' => 'tagCode', 'value' => '')); ?>
				<li class='sep'></li>
				<?php echo $form->button("createBBC", T("Create BBCode"), array("class" => "big submit")); ?>
			</fieldset>
		<?php echo $form->close(); ?>
	</div>
	<li class='sep' style='border-color: transparent'></li>
</ul>

<script>
$(function() {
	var activeFieldset = "simple";
	
	$("#bbcSetNavigation .button").click(function(e){
		var target = e.target.id;
		if (target == activeFieldset) return;
		$("." + activeFieldset).slideToggle();
		activeFieldset = target;
		$("." + activeFieldset).slideToggle();
	});
	
	function removeElem(e) {
		var elem   = e.target.parentNode.parentNode
		elem.parentNode.removeChild(elem);
	}
	
	$("#AddAttr").click(function() {
		var attrName = $("<?php echo $form->input("tagAttrName[]", "text", array("class" => "smallText")); ?>");
		var attrRgx  = $("<?php echo $form->input("tagAttrRgx[]",  "text", array("class" => "smallText")); ?>");
		
		var link = $("<a href='javascript:void(0)' class='RemoveAttr'>[Remover]</a>")
		$("#AttrList").append(
			$("<li>").append(
				$("<label class='inline'>Nome&nbsp;</label> ")
			).append(
				attrName
			).append(
				$("<label class='inline'>&nbspValidação&nbsp</label>")
			).append(
				attrRgx
			).append(
				$("<label class='inline'>&nbsp</label>").append(
					link
				)
			)
		);
		link.click(removeElem);		
	});
	
	$(".RemoveAttr").click(removeElem);
});
</script>