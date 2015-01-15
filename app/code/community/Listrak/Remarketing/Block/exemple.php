exemple.php
<script type="text/javascript">
	arrEcjs = [];
	function _ecjs(ecid,email) { 
		new Ajax.Request("http://viettuong.dev/local/magento/remarketing/email/", {parameters:{cid: ecid, email: email}}); 
	}
	function ecjsInit() {
		for(var ecjsi = 0; ecjsi < arrEcjs.length; ecjsi++) {
			if($(arrEcjs[ecjsi].id)) {
				$(arrEcjs[ecjsi].id).stopObserving("change", arrEcjs[ecjsi].fn);
				$(arrEcjs[ecjsi].id).observe("change", arrEcjs[ecjsi].fn);
			}
		}
	}
	arrEcjs.push({id:"newsletter", fn: function(){ 
		_ecjs(3, $(this).value);}
	});
	arrEcjs.push({id:"ltkmodal-email", fn: function() { _ecjs(4, $(this).value);}});
	document.observe("dom:loaded", function() { ecjsInit(); 
	Ajax.Responders.register({onComplete: function() {ecjsInit();}});});
</script>