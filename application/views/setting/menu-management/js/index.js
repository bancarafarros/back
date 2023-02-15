$(document).ready(function () {

  function expand() {
    $('.dd').nestable('expandAll');
  }

  function collapse() {
    $('.dd').nestable('collapseAll');
  }

  $("#expand").click(function (e) { 
    e.preventDefault();
    expand()
  });

  $("#collapse").click(function (e) { 
    e.preventDefault();
    collapse()
  });
  
$('.dd').nestable({
  maxDepth:3,
  callback: function(l,e){
        console.log(l)
        console.log(e)
    }
 });

 $('.fa-icon-picker').iconpicker({placement: "bottomLeft"});
 
});

