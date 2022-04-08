<?php

$current_page = isset($current_page) ? $current_page : "dashboard";
?>
<div class="sidebar">
	<a href="dashboard.php" class="logo text-center">
		<b>
			<h3>Sierra Leone Public Archives</h3>
		</b>
	</a>
	<div class="scrollbar-inner sidebar-wrapper">
		<ul class="nav">

			<li class="nav-item <?php echo $current_page == "dashboard" ? "active" : ""; ?>">
				<a href="dashboard.php">
					<i class="la la-dashboard"></i>
					<p>Dashboard</p>
				</a>
			</li>

			<li class="nav-item <?php echo $current_page == "upload" ? "active" : ""; ?>">
				<a href="upload.php">
					<i class="la la-circle"></i>
					<p>Upload Documents</p>
				</a>
			</li>

			<li class="nav-item <?php echo $current_page == "metatag" ? "active" : ""; ?>">
				<a href="metatag.php">
					<i class="la la-circle"></i>
					<p>Meta Tag Documents</p>
				</a>
			</li>


			<li class="nav-item <?php echo $current_page == "search" ? "active" : ""; ?>">
				<a href="search.php">
					<i class="la la-circle"></i>
					<p>Search/Filter</p>
				</a>
			</li>

			<li class="nav-item <?php echo $current_page == "vocabularies" ? "active" : ""; ?>">
				<a href="vocabularies.php">
					<i class="la la-circle"></i>
					<p>Edit Controlled Vocabularies </p>
				</a>
			</li>

			<li class="nav-item <?php echo $current_page == "users" ? "active" : ""; ?>">
				<a href="users.php">
					<i class="la la-circle"></i>
					<p>Add Users</p>
				</a>
			</li>
		</ul>
	</div>
</div>