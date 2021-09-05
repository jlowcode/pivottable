<?php

/**
 * Bootstrap pivottable viz template
 *
 * @package     Joomla.Plugin
 * @subpackage  Fabrik.visualization.pivottable
 * @copyright   Copyright (C) 2019  Projeto PITT - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

$row = $this->row;
$cache = JFactory::getCache('com_rsform');
//print_r($cache);
$cache->clean();
?>
<div id="<?php echo $this->containerId; ?>" class="fabrik_visualization">
	<?php if ($this->params->get('show-title', 1)) { ?>
		<h1><?php echo $row->label; ?></h1>
	<?php
	}
	?>
	<input id="root_url" type="hidden" class="root_url" value="<?php echo $this->urlbase ?>" />

	<?php
	$user = JFactory::getUser();
	//If the user is an administrator
	if (array_search(7, $user->groups) || array_search(8, $user->groups)) {
		echo "<p><button id='myBtn' type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal'>Atualizar Dados</button></p>" ?>
	<?php
	}
	?>

	<!-- Modal -->
	<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Gerar CSV</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Uma mensagem do tipo:<br>
						{"total":"","count":,"file":"-export.csv","limitStart":,"limitLength":}<br>
						Aparecerá no quadro abaixo quando o arquivo estiver pronto, portanto não feche essa janela!</p>
					<iframe id="iframe" height="100%" width="100%"></iframe>
				</div>
			</div>
		</div>
	</div>

	<script>
		// Get the modal
		var modal = document.getElementById("myModal");

		// Get the button that opens the modal
		var btn = document.getElementById("myBtn");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];

		// When the user clicks the button, open the modal 
		if (btn) {
			btn.onclick = function() {
				var url = "<?php echo $this->urlcontext ?>";
				modal.style.display = "block";
				var iframe = document.getElementById('iframe');
				iframe.src = url;
			};
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			modal.style.display = "none";
			window.location.reload(true);
		}
	</script>

	<?php echo '<script type="text/javascript" ' . 'src="plugins/fabrik_visualization/pivottable/dist/jquery-ui.min.js"></script>' ?>
	<div id="my-pivottable"></div>

</div>