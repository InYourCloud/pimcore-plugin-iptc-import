pimcore.registerNS("pimcore.plugin.iptcimport");

pimcore.plugin.iptcimport = Class.create(pimcore.plugin.admin, {
    getClassName: function() {
        return "pimcore.plugin.iptcimport";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
    },
 
    pimcoreReady: function (params,broker){
        // alert("IptcImport Ready!");
    }
});

var iptcimportPlugin = new pimcore.plugin.iptcimport();

