/*$(document).ready(function() {
    $('td input').bind('paste', null, function(e) {
        $input = $(this);
        setTimeout(function() {
            var values = $input.val().split(/\s+/),
                row = $input.closest('tr'),
                col = $input.closest('td').index();
            for (var i = 0; i < values.length; i++) {
                row.children().eq(col).find('input').val(values[i]);
                row = row.next();
            }
        }, 0);
    });
});*/

$('table').on('paste', 'input', function(e){
  var $this = $(this);
  $.each(e.originalEvent.clipboardData.items, function(i, v){
      if (v.type === 'text/plain'){
          v.getAsString(function(text){
              var x = $this.closest('td').index(),
                  y = $this.closest('tr').index()+1,
                  obj = {};
              text = text.trim('\r\n');
              $.each(text.split('\r\n'), function(i2, v2){
                  $.each(v2.split('\t'), function(i3, v3){
                      var row = y+i2, col = x+i3;
                      obj['cell-'+row+'-'+col] = v3;
                      $this.closest('table').find('tr:eq('+row+') td:eq('+col+') input').val(v3);
                  });
              });

          });
      }
  });
  return false;

});