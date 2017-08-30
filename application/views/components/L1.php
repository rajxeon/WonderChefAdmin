<html lang="en">
	<head>
		<?php $this->load->view('components/head'); ?>
	</head>

	<body class="no-skin">
		<?php $this->load->view('components/navbar'); ?>
		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>
			<?php $this->load->view('components/sidebar'); ?>
			
			<div class="main-content">
				<div class="main-content-inner">
					<?php $this->load->view('components/top'); ?>