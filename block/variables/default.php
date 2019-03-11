<script id="variable" type="text/jsx" >

var input = <?php echo $this->parameters(); ?>

var data = <?php echo $this->data(); ?>

var store = <?php echo $this->store(); ?>

var user = <?php echo $this->user(); ?>

var service = <?php echo $this->service(); ?>

var health = <?php echo $this->health(); ?>

var url = "<?php echo $this->url(); ?>";

const version = "0.000";

var search = "";

var page = 1;



    
</script>
<script>
function isClassComponent(component) {
    return (
        typeof component === 'function' && 
        !!component.prototype.isReactComponent
    ) ? true : false
}

function isFunctionComponent(component) {
    return (
        typeof component === 'function' && 
        String(component).includes('return React.createElement')
    ) ? true : false;
}

function isReactComponent(component) {
    return (
        isClassComponent(component) || 
        isFunctionComponent(component)
    ) ? true : false;
}

function isElement(element) {
    return React.isValidElement(element);
}

function isDOMTypeElement(element) {
    return isElement(element) && typeof element.type === 'string';
}

function isCompositeTypeElement(element) {
    return isElement(element) && typeof element.type === 'function';
}    
</script>
    