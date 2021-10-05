<?php
use \LightnCandy\SafeString as SafeString;use \LightnCandy\Runtime as LR;return function ($in = null, $options = null) {
    $helpers = array();
    $partials = array();
    $cx = array(
        'flags' => array(
            'jstrue' => false,
            'jsobj' => false,
            'jslen' => false,
            'spvar' => true,
            'prop' => false,
            'method' => false,
            'lambda' => false,
            'mustlok' => false,
            'mustlam' => false,
            'mustsec' => false,
            'echo' => false,
            'partnc' => false,
            'knohlp' => false,
            'debug' => isset($options['debug']) ? $options['debug'] : 1,
        ),
        'constants' => array(),
        'helpers' => isset($options['helpers']) ? array_merge($helpers, $options['helpers']) : $helpers,
        'partials' => isset($options['partials']) ? array_merge($partials, $options['partials']) : $partials,
        'scopes' => array(),
        'sp_vars' => isset($options['data']) ? array_merge(array('root' => $in), $options['data']) : array('root' => $in),
        'blparam' => array(),
        'partialid' => 0,
        'runtime' => '\LightnCandy\Runtime',
    );
    
    $inary=is_array($in);
    return ''.LR::sec($cx, (($inary && isset($in['events'])) ? $in['events'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);return '<div class="row event">
  <div class="col-sm-2">
'.((LR::ifvar($cx, (($inary && isset($in['current_day'])) ? $in['current_day'] : null), false)) ? '    <div class="current_day">
      <div class="day">'.htmlspecialchars((string)((isset($in['current_day']) && is_array($in['current_day']) && isset($in['current_day']['day'])) ? $in['current_day']['day'] : null), ENT_QUOTES, 'UTF-8').'</div>
      <div class="date">'.htmlspecialchars((string)((isset($in['current_day']) && is_array($in['current_day']) && isset($in['current_day']['date'])) ? $in['current_day']['date'] : null), ENT_QUOTES, 'UTF-8').'</div>
      <div class="month">'.htmlspecialchars((string)((isset($in['current_day']) && is_array($in['current_day']) && isset($in['current_day']['month'])) ? $in['current_day']['month'] : null), ENT_QUOTES, 'UTF-8').'</div>
    </div>
' : '').'  </div>
  <div class="col-sm-2 time">'.htmlspecialchars((string)(($inary && isset($in['start_time'])) ? $in['start_time'] : null), ENT_QUOTES, 'UTF-8').'-'.htmlspecialchars((string)(($inary && isset($in['end_time'])) ? $in['end_time'] : null), ENT_QUOTES, 'UTF-8').'</div>
  <div class="col-sm-8 details">
    <h2>'.(($inary && isset($in['title'])) ? $in['title'] : null).'</h2>
    <p><strong>Vendors:</strong></p>
    <ul>
'.LR::sec($cx, (($inary && isset($in['food_trucks'])) ? $in['food_trucks'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);return '      <li>'.$in.'</li>
';}).'  </ul>
   <p><strong>Location:</strong><br/>'.htmlspecialchars((string)((isset($in['location']) && is_array($in['location']) && isset($in['location']['name'])) ? $in['location']['name'] : null), ENT_QUOTES, 'UTF-8').'<br><a href="'.htmlspecialchars((string)((isset($in['location']) && is_array($in['location']) && isset($in['location']['link'])) ? $in['location']['link'] : null), ENT_QUOTES, 'UTF-8').'" target="_blank">'.((isset($in['location']) && is_array($in['location']) && isset($in['location']['address'])) ? $in['location']['address'] : null).'</a></p>
  </div>
</div>
';}).'';
};
?>