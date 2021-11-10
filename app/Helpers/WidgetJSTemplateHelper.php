<?php

namespace App\Helpers;

class WidgetJSTemplateHelper
{

    public static function getTpl($partnerId, $widgetId): string
    {
        $URL = config('app.url') . '/e.js';

        return <<<TPL

 <!-- adblm widget code -->
    <script>
      (function (w, d, t, r) {
        var adblm = w.adblm = function () {
          adblm.callMethod ? adblm.callMethod.apply(adblm, arguments) : adblm.queue.push(arguments)
        }
        adblm.push = adblm
        adblm.queue = []
        var n,
          i
        n = d.createElement(t)
        n.src = r
        n.async = 1
        i = d.getElementsByTagName(t)[0]
        i.parentNode.insertBefore(n, i)
      })(window, document, 'script', '{$URL}/e.js')

      const options = {
        partnerId: '{$partnerId}',
        widgetId: '{$widgetId}',
        // userId: 'userId',
        // clickId: 'clickId',
        // email: 'email@email.com',
        // country: 'UK',
        // zip: '0000000',
        // age: '20',
        // birthdate: '1990-01-01',
        // gender: 'male',
        // clickSub: 'clickSub'
      }

      adblm('init', 'adblm-widget', options)
    </script>

TPL;


    }
}
