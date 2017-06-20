<script>
window.analytics||(window.analytics=[]),window.analytics.methods=["identify","track","trackLink","trackForm","trackClick","trackSubmit","page","pageview","ab","alias","ready","group","on","once","off"],window.analytics.factory=function(t){return function(){var a=Array.prototype.slice.call(arguments);return a.unshift(t),window.analytics.push(a),window.analytics}};for(var i=0;window.analytics.methods.length>i;i++){var method=window.analytics.methods[i];window.analytics[method]=window.analytics.factory(method)}window.analytics.load=function(t){var a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=("https:"===document.location.protocol?"https://":"http://")+"d2dq2ahtl5zl1z.cloudfront.net/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n)},window.analytics.SNIPPET_VERSION="2.0.8",
window.analytics.load("cmwi3m8ekt");
window.analytics.page();
window.analytics.identify('<?php echo $this->userEmail; ?>',{name : '<?php echo $this->userName; ?>',email : '<?php echo $this->userEmail; ?>',accountId: '<?php echo $this->user_stripe_id; ?>',});
var grooveOnLoad = myInitFunction;
(function() {var s=document.createElement('script');
s.type='text/javascript';s.async=true;
s.src=('https:'==document.location.protocol?'https':'http') +
'://eyeq.groovehq.com/widgets/e3b05e27-c256-47e2-915f-d50bbd60e225/ticket.js'; var q = document.getElementsByTagName('script')[0];q.parentNode.insertBefore(s, q);})();
function myInitFunction() {
    GrooveWidget.options({name: "<?php echo $this->userName; ?>", email: "<?php echo $this->userEmail; ?>"});
}
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-78178512-1', 'auto');
  ga('send', 'pageview');

</script>