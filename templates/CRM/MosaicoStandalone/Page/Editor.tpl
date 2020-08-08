{foreach from=$scriptUrls item=scriptUrl}
  <script type="text/javascript" src="{$scriptUrl|htmlspecialchars}">
  </script>
{/foreach}
{foreach from=$styleUrls item=styleUrl}
  <link href="{$styleUrl|htmlspecialchars}" rel="stylesheet" type="text/css"/>
{/foreach}

{capture assign=msgTplURL}{crmURL p='civicrm/admin/messageTemplates' q="reset=1&activeTab=mosaico"}{/capture}
{literal}
<script type="text/javascript">
  $(function() {
    if (!Mosaico.isCompatible()) {
      alert('Your browser is out of date or you have incompatible plugins.  See https://civicrm.stackexchange.com/q/26118/225');
      return;
    }

    var CRM = parent.CRM;
    var closeIFrame = parent.closeIFrame;

    var plugins = [];
    var config = {/literal}{$mosaicoConfig}{literal};

    var actions = {
      save: function(ko, viewModel) {
        viewModel.metadata.changed = Date.now();

        var saveParams = {
          template_options: {
            mosaicoMetadata: viewModel.exportMetadata(),
            mosaicoContent: viewModel.exportJSON(),
            mosaicoTemplate: config.mosaicoTemplateName
          },
          body_html: viewModel.exportHTML()
        };
        if (config.hasOwnProperty('mosaicoMailingID')) {
          saveParams.id = config.mosaicoMailingID;
        }
        var savePromise = CRM.api3('Mailing', 'create', saveParams).done(function(result) {
          config.mosaicoMailingID = result.id;
          console.log('saved (' + result.id + ')');
        });
      }
    };

    plugins.push(function(viewModel) {
      mosaicoPlugin(ko, viewModel);
    });

    var ok = Mosaico.init(config, plugins);
    if (!ok) {
      console.log("Missing initialization hash, redirecting to main entrypoint");
    }

    if (config.hasOwnProperty('mosaicoTemplateMetadata')) {
      Mosaico.start(config, undefined, JSON.parse(config.mosaicoTemplateMetadata), JSON.parse(config.mosaicoTemplateContent), plugins);
    }
    else {
      Mosaico.start(config, config.mosaicoTemplatePath, undefined, undefined, plugins);
    }

    addCustomButton();

    // See https://github.com/voidlabs/mosaico/wiki/Mosaico-Plugins
    // Generally: Implement the in-dialog "Save" and "Test" buttons.
    function mosaicoPlugin(ko, viewModel) {
      // Clicking the default link isn't very useful in IFRAME context.
      viewModel.logoUrl = null;

      function mkCmd(name, callback) {
        var cmd = {
          name: name, // l10n happens in the template
          enabled: ko.observable(true)
        };
        cmd.execute = function() {
          cmd.enabled(false);
          callback(ko, viewModel);
          cmd.enabled(true);
        };
        return cmd;
      }

      if (actions.save) {
        viewModel.save = mkCmd("Save", actions.save);
      }
      if (actions.close) { // pretend like Mosaico has a "Close" action.
        viewModel.save = mkCmd("Close", actions.close);
      }
    }

    function addCustomButton() {
      var msgTplURL = "{/literal}{$msgTplURL}{literal}";
      if ($('#page .rightButtons').is(':visible')) {
        $("#page .rightButtons").append('<a href="#" title="Click to close" onclick="parent.closeIFrame();return false;" class="ui-button">Done</a>');
      } else {
        console.log('timeout 50');
        setTimeout(addCustomButton, 50);
      }
    }
  });
</script>
{/literal}
