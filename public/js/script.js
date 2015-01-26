
    function multiSelectDropdown(classCheckbox,valueDefault,idDisplay)
    {
        var valueDisplay='';
        var co=0;
        $(classCheckbox).each(function(){
          if($(this).is(':checked'))
          {
            co++;
            if(co>1)
            {
              valueDisplay+=', '; 
            }
            valueDisplay+=$(this).next("label").text();         
          }
        });
        if(valueDisplay=='')
        {
          valueDisplay=valueDefault;
        }
        var lengthSpan=valueDisplay.length;
        if(lengthSpan>25)
        {
          valueDisplay=valueDisplay.substring(0,25);
        }
        $(idDisplay).text(valueDisplay);
    }

    
     $( document ).ready(function() {
      $('.checkbox').checkbox();
      $('.ui.checkbox')
        .checkbox()
      ;
      $('select.dropdown')
        .dropdown()
      ;
      $('.ui.dropdown')
        .dropdown()
      ;
      $('.message .close').on('click', function() {
        $(this).closest('.message').fadeOut();
      });
      $('.menu .item')
        .tab()
      ;
    });