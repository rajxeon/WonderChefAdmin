<div id="sidebar" class="sidebar responsive ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<h3 style="margin: 7px;">Panels</h3>
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span> 

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				 <ul class="nav nav-list">
					<li class="">
						<a href="<?php echo site_url('welcome/dashboard'); ?>">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard </span>
						</a>

						<b class="arrow"></b>
					</li>
					
					<li class="">
						<a href="<?php echo site_url('welcome/posts'); ?>">
							<i class="menu-icon fa fa-user"></i>
							<span class="menu-text"> Associate Posts </span>
						</a>

						<b class="arrow"></b>
					</li>
					
					<li class="">
						<a href="<?php echo site_url('welcome/disciplines'); ?>">
							<i class="menu-icon fa fa-book"></i>
							<span class="menu-text"> Disciplines </span>
						</a>

						<b class="arrow"></b>
					</li>
					
					<li class="">
						<a href="<?php echo site_url('welcome/clients'); ?>">
							<i class="menu-icon fa fa-users"></i>
							<span class="menu-text"> Clients </span>
						</a>

						<b class="arrow"></b>
					</li>
				</ul>

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" 
					data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>
