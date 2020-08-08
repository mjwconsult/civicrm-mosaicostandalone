<div class="crm-mosaico-container">
  {capture assign=msgTplURL}{crmURL p='civicrm/admin/messageTemplates' q="reset=1&activeTab=mosaico"}{/capture}
  {literal}
    <script type="text/javascript">
      (function($) {
        var cfg = {
          url: '{/literal}{$mosaicoEditorIframe}{literal}',

          dimensions: function resize() {
            var c = $('.crm-mosaico-container').offset();
            var top = c.top, left = c.left, width = $(window).width(), height = $(window).height();
            height -= top;
            width -= left;
            return {position: 'fixed', left: left + 'px', top: top + 'px', width: width + 'px', height: height + 'px'};
          }
        };

        $iframe = $('<iframe class="crm-mosaico-iframe ui-front">');
        $('.crm-mosaico-container').append($iframe);
        onResize();
        $(window).on('resize', onResize);

        iframe = $iframe[0];
        iframe.setAttribute('src', cfg.url);

        function onResize() {
          if ($iframe) $iframe.css(cfg.dimensions());
        }

        function closeIFrame() {
          iframe.remove();
          // To be replaced with a sensible redirect
          window.location.replace('{/literal}{$msgTplURL}{literal}');
        }

      })(CRM.$);

      function closeIFrame() {
        CRM.$('<iframe class="crm-mosaico-iframe ui-front">').remove();
        // To be replaced with a sensible redirect
        window.location.replace('{/literal}{$msgTplURL}{literal}');
      }
    </script>
  {/literal}
</div>
