<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormDate extends AbstractHelper{
    public function __invoke($element){
        $filter = new \Zend\I18n\Filter\Alnum();  ?>
        <?php $id = $filter->filter($element->getAttribute('name')); ?>
        <div class="form-date" id="<?php echo $id ?>">
            <?php echo $this->view->formHidden($element); ?>
            <div class="jqxDate"></div>
            <script type="text/javascript">
                $('#<?php echo $id ?>.form-date .jqxDate').jqxDateTimeInput({
                    width: '250px', height: '25px',
                    value: setValue('<?php echo $element->getValue(); ?>')
                });
                $('#<?php echo $id ?>.form-date .jqxDate').on('valueChanged', function (event) {
                    var date = event.args.date;
                    var dateString = date.getFullYear() + '-' + (date.getMonth()+1) + '-' + date.getDate();
                    $('input[name="<?php echo $element->getAttribute('name'); ?>"]').val(dateString);
                });

                function setValue(val){
                    if(val)
                        return $.jqx._jqxDateTimeInput.getDateTime(new Date(val));
                    return null;
                }
            </script>
        </div>
        
    <?php }    
}