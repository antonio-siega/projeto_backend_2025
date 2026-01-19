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
    var lote = document.getElementById('lote');
    var quantidade = document.getElementById('quantidade');
    var vb = document.getElementById('valor_bruto');
    var taxa = document.getElementById('taxa');
    var desconto = document.getElementById('desconto');
    var total = document.getElementById('total_liquido');
    if(!vb || !taxa || !desconto || !total) return;

    function calcValorBruto(){
      if(!lote || !quantidade) return;
      
      var selectedOption = lote.options[lote.selectedIndex];
      var preco = parseVal(selectedOption.getAttribute('data-preco'));
      var qtd = parseVal(quantidade.value);
      var valorBruto = preco * qtd;
      
      vb.value = isNaN(valorBruto) ? '' : formatVal(valorBruto);
      calcTotal();
    }

    function calcTotal(){
      var v = parseVal(vb.value);
      var t = parseVal(taxa.value);
      var d = parseVal(desconto.value);
      var res = v + t - d;
      total.value = isNaN(res) ? '' : formatVal(res);
    }

    // Eventos para atualizar valor bruto
    if(lote) {
      lote.addEventListener('change', calcValorBruto);
    }
    if(quantidade) {
      ['input','change','blur'].forEach(function(evt){
        quantidade.addEventListener(evt, calcValorBruto);
      });
    }

    // Eventos para calcular total líquido
    ['input','change','blur'].forEach(function(evt){
      vb.addEventListener(evt, calcTotal);
      taxa.addEventListener(evt, calcTotal);
      desconto.addEventListener(evt, calcTotal);
    });

    // calcula inicial se houver valores preenchidos
    calcValorBruto();
  });
})();
