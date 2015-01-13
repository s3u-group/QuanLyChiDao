<?php
namespace Application\Form\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Date extends AbstractHelper{
    public function __invoke($att){
        $name = $att['name'];
        $width = isset($att['width'])?$att['width']:'250px';
        $value = $att['value']; // Y-m-d
        $filter = new \Zend\I18n\Filter\Alnum();  ?>
        <?php if(isset($att['id'])) $id = $att['id']; else $id = $filter->filter($name); ?>
        <div class="date" id="wrap_<?php echo $id ?>">
            <input type="hidden" name="<?php echo $name ?>" id="<?php echo $id ?>">
            <div class="jqxDate"></div>
            <script type="text/javascript">
                $('#wrap_<?php echo $id ?>.date .jqxDate').jqxDateTimeInput({
                    width: '<?php echo $width ?>', height: '25px',
                    value: setValue('<?php echo $value; ?>')
                });
                $('#wrap_<?php echo $id ?>.date .jqxDate').on('valueChanged', function (event) {
                    var date = event.args.date;
                    var dateString = date.getFullYear() + '-' + pad((date.getMonth()+1),2) + '-' + pad(date.getDate(),2);
                    $('input#<?php echo $id ?>').val(dateString);
                });

                function setValue(val){
                    if(val)
                        return $.jqx._jqxDateTimeInput.getDateTime(new Date(val));
                    return null;
                }

                function pad (str, max) {
                    str = str.toString();
                    return str.length < max ? pad("0" + str, max) : str;
                }
            </script>
        </div>
        
    <?php }    
}