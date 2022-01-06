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
    return ''.((LR::ifvar($cx, (($inary && isset($in['events'])) ? $in['events'] : null), false)) ? ''.LR::sec($cx, (($inary && isset($in['events'])) ? $in['events'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);return '  <div class="row event desktop'.((LR::ifvar($cx, (($inary && isset($in['css_classes'])) ? $in['css_classes'] : null), false)) ? ' '.htmlspecialchars((string)(($inary && isset($in['css_classes'])) ? $in['css_classes'] : null), ENT_QUOTES, 'UTF-8').'' : '').'">
    '.((LR::ifvar($cx, (($inary && isset($in['cancelled'])) ? $in['cancelled'] : null), false)) ? '<div class="banner cancelled">Cancelled <span>Inclement Weather</span></div>' : '').'
    <div class="col-sm-2">
'.((LR::ifvar($cx, ((isset($in['current_day']) && is_array($in['current_day']) && isset($in['current_day']['display'])) ? $in['current_day']['display'] : null), false)) ? '      <div class="current_day">
        <div class="day">'.htmlspecialchars((string)((isset($in['current_day']) && is_array($in['current_day']) && isset($in['current_day']['day'])) ? $in['current_day']['day'] : null), ENT_QUOTES, 'UTF-8').'</div>
        <div class="date">'.htmlspecialchars((string)((isset($in['current_day']) && is_array($in['current_day']) && isset($in['current_day']['date'])) ? $in['current_day']['date'] : null), ENT_QUOTES, 'UTF-8').'</div>
        <div class="month">'.htmlspecialchars((string)((isset($in['current_day']) && is_array($in['current_day']) && isset($in['current_day']['month'])) ? $in['current_day']['month'] : null), ENT_QUOTES, 'UTF-8').'</div>
      </div>
' : '').'    </div>
    <div class="col-sm-3 time">
      '.htmlspecialchars((string)(($inary && isset($in['start_time'])) ? $in['start_time'] : null), ENT_QUOTES, 'UTF-8').'-'.htmlspecialchars((string)(($inary && isset($in['end_time'])) ? $in['end_time'] : null), ENT_QUOTES, 'UTF-8').'
    </div>
    <div class="col-sm-7 details">
      <p class="location"><a href="'.htmlspecialchars((string)((isset($in['location']) && is_array($in['location']) && isset($in['location']['link'])) ? $in['location']['link'] : null), ENT_QUOTES, 'UTF-8').'" target="_blank">
'.((LR::ifvar($cx, ((isset($in['location']) && is_array($in['location']) && isset($in['location']['thumbnail'])) ? $in['location']['thumbnail'] : null), false)) ? '        <img src="'.htmlspecialchars((string)((isset($in['location']) && is_array($in['location']) && isset($in['location']['thumbnail'])) ? $in['location']['thumbnail'] : null), ENT_QUOTES, 'UTF-8').'" style="max-width: 200px; height: auto;" />
' : '        '.htmlspecialchars((string)((isset($in['location']) && is_array($in['location']) && isset($in['location']['name'])) ? $in['location']['name'] : null), ENT_QUOTES, 'UTF-8').'
').'      <br>'.((isset($in['location']) && is_array($in['location']) && isset($in['location']['address'])) ? $in['location']['address'] : null).'</a>
      </p>
      <p class="heading">Food Trucks:</p>
'.LR::sec($cx, (($inary && isset($in['food_trucks'])) ? $in['food_trucks'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);return '        <div class="row foodtruck">
          <div class="col-xs-5"><strong>'.((LR::ifvar($cx, (($inary && isset($in['website'])) ? $in['website'] : null), false)) ? '<a href="'.htmlspecialchars((string)(($inary && isset($in['website'])) ? $in['website'] : null), ENT_QUOTES, 'UTF-8').'" target="_blank">' : '').''.(($inary && isset($in['name'])) ? $in['name'] : null).''.((LR::ifvar($cx, (($inary && isset($in['website'])) ? $in['website'] : null), false)) ? '</a>' : '').'</strong></div>
          <div class="col-xs-7">'.htmlspecialchars((string)(($inary && isset($in['short_description'])) ? $in['short_description'] : null), ENT_QUOTES, 'UTF-8').'</div>
        </div>
';}).'    </div>
  </div>
  <div class="event mobile'.((LR::ifvar($cx, (($inary && isset($in['css_classes'])) ? $in['css_classes'] : null), false)) ? ' '.htmlspecialchars((string)(($inary && isset($in['css_classes'])) ? $in['css_classes'] : null), ENT_QUOTES, 'UTF-8').'' : '').'">
    '.((LR::ifvar($cx, (($inary && isset($in['cancelled'])) ? $in['cancelled'] : null), false)) ? '<div class="banner cancelled">Cancelled <span>Inclement Weather</span></div>' : '').'
    <div class="row">
      <div class="col-xs-3">
        <div class="current_day">
          <div class="day">'.htmlspecialchars((string)((isset($in['current_day']) && is_array($in['current_day']) && isset($in['current_day']['day'])) ? $in['current_day']['day'] : null), ENT_QUOTES, 'UTF-8').'</div>
          <div class="date">'.htmlspecialchars((string)((isset($in['current_day']) && is_array($in['current_day']) && isset($in['current_day']['date'])) ? $in['current_day']['date'] : null), ENT_QUOTES, 'UTF-8').'</div>
          <div class="month">'.htmlspecialchars((string)((isset($in['current_day']) && is_array($in['current_day']) && isset($in['current_day']['month'])) ? $in['current_day']['month'] : null), ENT_QUOTES, 'UTF-8').'</div>
        </div>
        <div class="time">'.htmlspecialchars((string)(($inary && isset($in['start_time'])) ? $in['start_time'] : null), ENT_QUOTES, 'UTF-8').'-'.htmlspecialchars((string)(($inary && isset($in['end_time'])) ? $in['end_time'] : null), ENT_QUOTES, 'UTF-8').'</div>
      </div>
      <div class="col-xs-9">

        <p class="location"><a href="'.htmlspecialchars((string)((isset($in['location']) && is_array($in['location']) && isset($in['location']['link'])) ? $in['location']['link'] : null), ENT_QUOTES, 'UTF-8').'" target="_blank">
'.((LR::ifvar($cx, ((isset($in['location']) && is_array($in['location']) && isset($in['location']['thumbnail'])) ? $in['location']['thumbnail'] : null), false)) ? '          <img src="'.htmlspecialchars((string)((isset($in['location']) && is_array($in['location']) && isset($in['location']['thumbnail'])) ? $in['location']['thumbnail'] : null), ENT_QUOTES, 'UTF-8').'" style="max-width: 200px; height: auto;" />
' : '          '.htmlspecialchars((string)((isset($in['location']) && is_array($in['location']) && isset($in['location']['name'])) ? $in['location']['name'] : null), ENT_QUOTES, 'UTF-8').'
').'        <br>'.((isset($in['location']) && is_array($in['location']) && isset($in['location']['address'])) ? $in['location']['address'] : null).'</a>
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <p class="heading">Food Trucks:</p>
'.LR::sec($cx, (($inary && isset($in['food_trucks'])) ? $in['food_trucks'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);return '          <div class="row" style="margin-bottom: .5em;">
            <div class="col-xs-12"><strong>'.((LR::ifvar($cx, (($inary && isset($in['website'])) ? $in['website'] : null), false)) ? '<a href="'.htmlspecialchars((string)(($inary && isset($in['website'])) ? $in['website'] : null), ENT_QUOTES, 'UTF-8').'" target="_blank">' : '').''.(($inary && isset($in['name'])) ? $in['name'] : null).''.((LR::ifvar($cx, (($inary && isset($in['website'])) ? $in['website'] : null), false)) ? '</a>' : '').'</strong>'.((LR::ifvar($cx, (($inary && isset($in['short_description'])) ? $in['short_description'] : null), false)) ? '<br>'.htmlspecialchars((string)(($inary && isset($in['short_description'])) ? $in['short_description'] : null), ENT_QUOTES, 'UTF-8').'' : '').'</div>
          </div>
';}).'      </div>
    </div>
  </div>
';}).'' : '  '.(($inary && isset($in['no_events'])) ? $in['no_events'] : null).'
').'';
};
?>