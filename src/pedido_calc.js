(function(){
  function parseVal(v){
    if(v === null || v === undefined) return 0;
    v = String(v).trim().replace(',', '.');
    if(v === '') return 0;
    var n = parseFloat(v);
    return isNaN(n) ? 0 : n;
  }
  function formatVal(n){
    return Number(n).toFixed(2);
  }

  document.addEventListener('DOMContentLoaded', function(){
    var vb = document.getElementById('valor_bruto');
    var taxa = document.getElementById('taxa');
    var desconto = document.getElementById('desconto');
    var total = document.getElementById('total_liquido');
    if(!vb || !taxa || !desconto || !total) return;

    function calc(){
      var v = parseVal(vb.value);
      var t = parseVal(taxa.value);
      var d = parseVal(desconto.value);
      var res = v + t - d;
      total.value = isNaN(res) ? '' : formatVal(res);
    }

    ['input','change','blur'].forEach(function(evt){
      vb.addEventListener(evt, calc);
      taxa.addEventListener(evt, calc);
      desconto.addEventListener(evt, calc);
    });

    // calcula inicial se houver valores preenchidos
    calc();
  });
})();
