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
    return '<h3>Schedule a Consultation</h3>
<p>Schedule an appointment with '.htmlspecialchars((string)(($inary && isset($in['title'])) ? $in['title'] : null), ENT_QUOTES, 'UTF-8').' by clicking below. Then select your preferred date and time. If you don\'t have a preference, or don\'t see your preferred time available, please add a note to the submission.</p>
<div class="elementor-button-wrapper">
  <a href="'.htmlspecialchars((string)(($inary && isset($in['alt_link'])) ? $in['alt_link'] : null), ENT_QUOTES, 'UTF-8').'" target="'.htmlspecialchars((string)(($inary && isset($in['target'])) ? $in['target'] : null), ENT_QUOTES, 'UTF-8').'" class="elementor-button-link elementor-button elementor-size-'.htmlspecialchars((string)(($inary && isset($in['size'])) ? $in['size'] : null), ENT_QUOTES, 'UTF-8').'" '.((LR::ifvar($cx, (($inary && isset($in['style'])) ? $in['style'] : null), false)) ? 'style="'.htmlspecialchars((string)(($inary && isset($in['style'])) ? $in['style'] : null), ENT_QUOTES, 'UTF-8').'"' : '').' role="button">
    <span class="elementor-button-content-wrapper">
'.((LR::ifvar($cx, (($inary && isset($in['icon'])) ? $in['icon'] : null), false)) ? '      <span class="elementor-button-icon elementor-align-icon-'.htmlspecialchars((string)(($inary && isset($in['icon_align'])) ? $in['icon_align'] : null), ENT_QUOTES, 'UTF-8').'">
        <i aria-hidden="true" class="fas fa-'.htmlspecialchars((string)(($inary && isset($in['icon'])) ? $in['icon'] : null), ENT_QUOTES, 'UTF-8').'"></i>
      </span>
' : '').'      <span class="elementor-button-text">'.htmlspecialchars((string)(($inary && isset($in['text'])) ? $in['text'] : null), ENT_QUOTES, 'UTF-8').'</span>
    </span>
  </a>
</div>';
};
?>