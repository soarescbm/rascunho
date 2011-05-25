<?php


class P2s_Html_Table
{
	protected $_tag;
	
	public function __construct()
	{
		$this->_tag = new P2s_Html_Tag();
	}
	
	public function create(array $data, array $properties = null)
	{
		if (empty($data)) return '';
		
		$null = array();		
		
		$html = '';

		$line = '';
		
		foreach($data as $record) 
		{			
			foreach($record as $fieldName => $value)
			{
				$line.= $this->_tag->getTag('th',$properties['th'][$fieldName]== null? array('align'=>'center','nowrap'=>'nowrap', 'width'=>'20px'):$properties['th'][$fieldName],$fieldName);
			}
                         $line.= $this->_tag->getTag('th',$null,'&nbsp;');
			break;		
		}
    $line = $this->_tag->getTag('tr',array(),$line);
		$html .= $line;

                $n = 0;
		foreach($data as $record) 
		{
			$line = '';
                        foreach($record as $key => $value)
			{				
				$value = empty($value) ? '&nbsp;' : $value;
				$line.= $this->_tag->getTag('td',$properties['th'][$key]== null? array('align'=>'center','nowrap'=>'nowrap'):$properties['th'][$key],$value);

			}
                        $line.= $this->_tag->getTag('td',$null,'&nbsp;');
                        //linha
                        if($n == 1) { $n--;} else {  $n++; }
                        $row = array('class' => 'row'.$n);
			$html.= $this->_tag->getTag('tr',$row,$line);
		}
                foreach($data as $record)
		{       $line = '';
			foreach($record as $fieldName => $value)
			{
				$line.= $this->_tag->getTag('th',$null, '&nbsp;');
			}
                         $line.= $this->_tag->getTag('th',$null,'&nbsp;');
			break;
		}
		$html .= $this->_tag->getTag('tr',$null,$line);
		
		$html = $this->_tag->getTag('table',$properties['table'] == null ? array( 'width'=>'100','cellspacing'=>'0', 'cellpadding'=>'0', 'class'=>'adminlist') : $properties['table'] ,$html);

		return $html;		
	}
}	

?>
