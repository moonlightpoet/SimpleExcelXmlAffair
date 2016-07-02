<?php
	require('litbheader.php');
?>

						<article class="post post-3">
							<header class="entry-header">
								<h1 class="entry-title">
									<a href="single.html">Choose Files and Click OK:)</a>
								</h1>
							</header>
							<div class="entry-content clearfix">
								<div class="read-more cl-effect-14">
	
	<table width="500px" border="0px">  
	<form action="upload.php" method="post" enctype="multipart/form-data">
		<tr>
		<td>xml file:</td>
		<td><input type="file" name="xmlfile" id=""></td>
		</tr>
		<tr>
		<td>excel file:</td>
		<td><input type="file" name="excelfile" id=""></td>
		</tr>
		<!-- tr>
		<td>utm campaign:</td>
		<td><input type="text" name="utmcampaign" id="" value="MITB20151208"></td>
		</tr -->
		<tr>
		<td>filename</td>
		<td><input type="text" name="filename" id=""></td>
		</tr>
		<tr>
		<td>username:</td>
		<td><input type="text" name="username" id=""></td>
		</tr>
		<tr>
		<tr>
		<td><input type="submit" value="OK"></td>
		</tr>
	</form>
	</table>
								</div>
							</div>
						</article>
<?php
	require('litbfooter.php');
?>
