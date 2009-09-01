<?php
/*
 * Instead of an output filter it would be smart to have a nice template operator later on that directly converts the urls
 * 
 */
class xrowCDNfilter
{
	const DIR_NAME     = '[a-z0-9_-]+';
	const PATH_EXP     = '(\/[a-z0-9_-]+)*';
	const BASENAME_EXP = '[.a-z0-9_-]+';
	
	static function buildRegExp( $dirs, $suffixes )
	{

        $dirs     = '(' . implode('|', $dirs ) . ')';
        $suffixes = '(' . implode('|', $suffixes ) . ')';
        // [shu][r][cel] improves performance
        return "/([shu][r][cel])(=['\"]|f=['\"]|(\s)*\((\s)*['\"]?(\s)*)(" . $dirs . self::PATH_EXP . '\/' . self::BASENAME_EXP . '\.'. $suffixes .')/imU';
	} 
    static function filter( $output )
    {
    	
        eZDebug::createAccumulatorGroup( 'outputfilter_total', 'Outputfilter Total' );
        
        $ini          = eZINI::instance( 'xrowcdn.ini' );
        $patterns     = array();
        $replacements = array();

        if( $ini->hasVariable( 'Rules', 'List' ) )
        {
        	foreach( $ini->variable( 'Rules', 'List' ) as $rule )
        	{
        		$dirs   = array();
        		$suffix = array();
        		
        		if( $ini->hasSection( 'Rule-' . $rule ) )
        		{
        			if( $ini->hasVariable( 'Rule-' . $rule, 'Dirs' ) AND $ini->hasVariable( 'Rule-' . $rule, 'Suffixes' ) AND $ini->hasVariable( 'Rule-' . $rule, 'Replacement' ) )
        			{
        				$dirs           = $ini->variable( 'Rule-' . $rule, 'Dirs' );
        				$suffix         = $ini->variable( 'Rule-' . $rule, 'Suffixes' );
        				$patterns[]     = self::buildRegExp( $dirs, $suffix);
        				$replacements[] = '\1\2' . $ini->variable( 'Rule-' . $rule, 'Replacement' ) . '\6';
        			}
        		}
        	} // FOREACH
        } // IF ends

        eZDebug::accumulatorStart( 'outputfilter', 'outputfilter_total', 'Output Filtering' );
            $out = preg_replace($patterns, $replacements, $output );
    	eZDebug::accumulatorStop( 'outputfilter' );

    	return $out;
    }
}
?>