<?php
$thisfile_meta=basename(__FILE__, ".php");

# register plugin
register_plugin(
	$thisfile_meta,
	'MetaRobots',
	'1.1',
	'Mike Henken',
	'http://michaelhenken.com.',
	'Choose pages that should not be followed by search engines',
	'pages',
	'meta_showChoice'
);

add_action('theme-header','meta_showTag'); 
add_action('edit-extras','meta_showChoice');
add_action('changedata-save','meta_processChoice'); 


function checkMetaRobots($id)
{
	$current_page_edit =$id;
	$the_page_slug_xml = GSDATAPAGESPATH.$current_page_edit.'.xml';
	$page_data = getXML($the_page_slug_xml);

	if($page_data->metarobots == 'yes')
	{
		return true;
	}
	else
	{
		return false;
	}
}

function meta_showChoice()
{
	$metaChecked = '';
	if(isset($_GET['id']))
	{
		$checkMetaRobots = checkMetaRobots($_GET['id']);
		if($checkMetaRobots == true)
		{
			$metaChecked = 'checked';
		}
	}
	?>
	<div style="width:100%">
		<p class="inline post-menu clearfix">
					<input type="checkbox" id="" style="width: 20px;padding: 0;margin: 0;" value="yes" name="metaRobots" <?php echo $metaChecked; ?>>&nbsp;&nbsp;&nbsp;<label for="metaRobots">Enable NoIndex &amp; NoFollow</label>
		</p>
			<div style="clear:both;"></div>
	</div> 
	<?php
}

function meta_processChoice()
{
	global $xml;
	if(isset($_POST['metaRobots']))
	{ 
		$node = $xml->addChild(strtolower('metarobots'))->addCData(stripslashes($_POST['metaRobots']));	
	}
}

function meta_showTag()
{
	$checkMetaRobots = checkMetaRobots(return_page_slug());
	if($checkMetaRobots == true)
	{
		$metaChecked = '<meta name="robots" content="noindex, nofollow">';
	}
	else
	{
		$metaChecked = '<meta name="robots" content="index, follow" />';
	}
	echo $metaChecked;
}