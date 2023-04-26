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
    return ''.((LR::ifvar($cx, (($inary && isset($in['events'])) ? $in['events'] : null), false)) ? '  <div class="row events-list-two">
'.LR::sec($cx, (($inary && isset($in['events'])) ? $in['events'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);return '    <div class="col-sm-3 event'.((LR::ifvar($cx, (($inary && isset($in['css_classes'])) ? $in['css_classes'] : null), false)) ? ' '.htmlspecialchars((string)(($inary && isset($in['css_classes'])) ? $in['css_classes'] : null), ENT_QUOTES, 'UTF-8').'' : '').'">
      <a class="share-link" href="#" onclick="openWindow(event, \'https://www.facebook.com/sharer/sharer.php?u='.htmlspecialchars((string)(($inary && isset($in['permalink'])) ? $in['permalink'] : null), ENT_QUOTES, 'UTF-8').'\')" target="_blank">Click Here to Share</a>
      <div class="border">
        '.((LR::ifvar($cx, (($inary && isset($in['cancelled'])) ? $in['cancelled'] : null), false)) ? '<div class="banner cancelled">Cancelled <span>Inclement Weather</span></div>' : '').'
        <div class="date">'.htmlspecialchars((string)((isset($in['current_day']) && is_array($in['current_day']) && isset($in['current_day']['fulldate'])) ? $in['current_day']['fulldate'] : null), ENT_QUOTES, 'UTF-8').'</div>
        <div class="foodtrucks">
'.LR::sec($cx, (($inary && isset($in['food_trucks'])) ? $in['food_trucks'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);return '            <div class="row foodtruck">
              '.((LR::ifvar($cx, (($inary && isset($in['website'])) ? $in['website'] : null), false)) ? '<a href="'.htmlspecialchars((string)(($inary && isset($in['website'])) ? $in['website'] : null), ENT_QUOTES, 'UTF-8').'" target="_blank">' : '').'<img src="'.htmlspecialchars((string)(($inary && isset($in['thumbnail'])) ? $in['thumbnail'] : null), ENT_QUOTES, 'UTF-8').'" alt="'.htmlspecialchars((string)(($inary && isset($in['name'])) ? $in['name'] : null), ENT_QUOTES, 'UTF-8').'" />'.((LR::ifvar($cx, (($inary && isset($in['website'])) ? $in['website'] : null), false)) ? '</a>' : '').'
            </div>
';}).'        </div><!-- .foodtrucks -->
      </div><!-- .border -->
    </div><!-- .col-sm-3.event -->
';}).'  </div>
' : '  '.(($inary && isset($in['no_events'])) ? $in['no_events'] : null).'  
').'';
};
?>