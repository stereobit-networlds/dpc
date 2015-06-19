<?php

// +----------------------------------------------------------------------+
// | Author: Charly Pache <3d@pache.ch>                                   |
// +----------------------------------------------------------------------+

$NL = "\n";
class api_vrml
{
	var $world_info;
	var $nodes;
	var $file_name;
	
	var $datadir;
		
	function api_vrml($p_title="Title",$p_info=array("Info"),$p_file_name="test.wrl")
	{
		$this->world_info = new WorldInfo($p_title,$p_info);
		$this->nodes = array();
		$this->addNode($this->world_info);
		$this->file_name = $p_file_name;
		
	    $this->datadir = paramload('SHELL','prpath');// . 'public/';			
	}
	/**
	 * On peut envoyer un noeud ou un tableau de noeuds
	 * en paramètre	  
	 *
	 **/
	
	function addNode($p_node)
	{
		if(is_array($p_node))
		{
			foreach($p_node as $node)
				array_push($this->nodes,$node);
		}
		else
		{
			array_push($this->nodes,$p_node);
		}
	}
	
	function generate()
	{
		$page = "#VRML V2.0 utf8";
		foreach($this->nodes as $node)
		{
			$page .= $node->getNode();
		}
		$fp = fopen ($this->datadir.$this->file_name, "w");
		fwrite($fp,$page);
		fclose($fp);
	}
	
}
class Node
{
	var $attributes = array();
	var $node_name = "";
	var $sub_nodes = array();
	
	function Node()
	{
		$this->sub_nodes = array();
	}
	function addNode($p_node)
	{
		array_push($this->sub_nodes,$p_node);
	}
	function getNode()
	{
		GLOBAL $NL;
		$page = $NL.$this->node_name."{";
		foreach ($this->attributes as $name => $value)
		{
			if(is_array($value))
			{
				$page .= $NL.$name." [";
				foreach ($value as $single_value)
				{
					if(is_object($single_value))
					{
						$page.= $NL." ".$single_value->getNode();
					}
					else
					{
						$page .= $NL."".$single_value;
					}
					
				}
				$page .= $NL."]";			
			}
			else if(is_object($value))
			{
				$page.= $NL.$name." ".$value->getNode();
			}
			else
			{
				$page .= $NL.$name." ".$value;
			}		
		}
		$page .= $NL."}";
		return $page;
	}
}
class WorldInfo extends Node
{
	function WorldInfo($p_title="",$p_info=array())
	{
		$this->attributes = array("title"=>$p_title,"info"=>$p_info);
		$this->node_name = "WorldInfo";
	}	
}
class Shape extends Node
{
	function Shape($p_appearance="NULL",$p_geometry="NULL")
	{
		$this->attributes = array("appearance"=>$p_appearance,"geometry"=>$p_geometry);
		$this->node_name = "Shape";
	}

}
class Box extends Node
{
	function Box($p_size="")
	{
		$this->attributes = array("size"=>$p_size);
		$this->node_name = "Box";
	}
}
class Sphere extends Node
{
	function Sphere($p_radius="1")
	{
		$this->attributes = array("radius"=>$p_radius);
		$this->node_name = "Sphere";
	}
}
class Cylinder extends Node
{
	function Cylinder($p_radius="1.0",$p_height="2.0",$p_side="TRUE",$p_bottom="TRUE",$p_top="TRUE")
	{
		$this->attributes = array("radius"=>$p_radius,"height"=>$p_height,"side"=>$p_side,"bottom"=>$p_bottom,"top"=>$p_top);
		$this->node_name = "Cylinder";
	}

}
class Script extends Node
{
	/*
		Script { 
		url           [] 
		directOutput  FALSE
		mustEvaluate  FALSE
		# And any number of:
		eventIn      eventType eventName
		field        fieldType fieldName initialValue
		eventOut     eventType eventName
		}
	*/
}
class Appearance extends Node
{
	function Appearance($p_material="NULL",$p_texture="NULL",$p_textureTransform="NULL")
	{
		$this->attributes = array("material"=>$p_material,"texture"=>$p_texture,"textureTransform"=>$p_textureTransform);
		$this->node_name = "Appearance";
	}

}  
class Material extends Node
{
	
	function Material($p_diffuseColor="0.8 0.8 0.8",$p_ambientIntensity="0.2",$p_emissiveColor="0.0 0.0 0.0",$p_specularColor="0.0 0.0 0.0",$p_shininess="0.2",$p_transparency="0.0")
	{
		$this->attributes = array("diffuseColor"=>$p_diffuseColor,"ambientIntensity"=>$p_ambientIntensity,"emissiveColor"=>$p_emissiveColor,"specularColor"=>$p_specularColor,"shininess"=>$p_shininess,"transparency"=>$p_transparency);
		$this->node_name = "Material";
	}
	function setTransparency($p_transparency)
	{
		$this->attributes["transparency"] = $p_transparency;
		
	}
}

class Transform extends Node
{
	// transgression : on a mis l'attribut children au début :
	function Transform($p_children="NULL",$p_scale="1 1 1",$p_scaleOrientation="0 0 1 0",$p_center="0 0 0",$p_rotation="0 0 1 0",$p_translation="4 0 0",$p_bboxCenter="0 0 0",$p_bboxSize="-1 -1 -1")
	{
		$this->attributes = array("scale"=>$p_scale,"scaleOrientation"=>$p_scaleOrientation,"center"=>$p_center,"rotation"=>$p_rotation,"translation"=>$p_translation,"bboxCenter"=>$p_bboxCenter,"bboxSize"=>$p_bboxSize,"children"=>$p_children);
		$this->node_name= "Transform";
	}
	function setTranslation($p_translation)
	{
		$this->attributes["translation"] = $p_translation;
	}
	
}

?>