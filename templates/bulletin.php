<!DOCTYPE html>
<html lang="es">

<head>
	<title>SG Bulletin - Week 1 - 2016</title>
	<meta charset="utf-8">	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	<!-- Latest compiled and minified JavaScript -->
	<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>-->
	<style>
		.table > tbody > tr > td {
     		vertical-align: middle;
		}
	</style>

</head>

<body>
	<div class="container">
	  <div class="jumbotron text-center">
	    <h1>Solid Gear Bulletin</h1> 
	    <p>Every Monday, we send our newsletter subscribers a free round-up of latest technology trend news and must-reads. It’s a curated digest that enables you to stay updated while saving time.</p>
		<p>It is compiled with suggestions from SolidGear team members.</p> 
	  </div>
		<h2>Week <?php echo date("W - Y"); ?></h2>
		<div class="table-responsive">          
			<table class="table table-striped">
			<thead>
			  <tr>
			    <th>Title</th>
			    <th>Description</th>
			    <th>Thumbnail</th>
			    <th>Tags</th>
			    <th>Reporter</th>			    
			  </tr>
			</thead>
			<tbody>
		    <?php foreach($data as $editor): ?>
		    	<?php foreach($editor as $entry): ?>
				  <tr>
				    <td><a href="<?php echo $entry['url'];?>"><?php echo $entry['title'];?></a></td>
				    <td><?php echo $entry['description'];?></td>
				    <td><a href="<?php echo $entry['url'];?>"><img src="<?php echo 'data:image/jpg;base64,'.base64_encode(file_get_contents($entry['thumb']));?>" height="113" width="150"></a></td>
				    <td><?php echo $entry['tags'];?></td>
				    <td><a href="https://twitter.com/<?php echo $entry['twitter'];?>"><span class="label label-primary"><?php echo $entry['twitter'];?></span></a></td>
				  </tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
			</tbody>
		</table>
		</div>  
	</div>

</body>

</html>
