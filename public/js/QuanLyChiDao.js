
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

    function showTable(id)
    {    
      $('#tblChuaXem').hide();
      $('#tblDangXuLy').hide();
      $('#tblTreHan').hide();
      $('#tblTatCa').hide();
      $(id).show();
    }
    function showTableNhatKy(id)
    {    
      $('#tblChuaXem').hide();
      $('#tblDangXuLy').hide();
      $('#tblTreHan').hide();
      $('#tblHoanThanh').hide();
      $(id).show();
    }