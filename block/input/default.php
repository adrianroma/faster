<style>
    
    
    .inputText{
        
        background-color:silver;
        float:left;
        margin-top:200px;
       }
    
   
    
    @media (max-width: 12450px) { 
    
     .inputText{
        
        background-color:silver;
       }
    
    }
    
    
     @media (max-width: 800px) { 
     
        .inputText{
        
        background-color:skyblue;
       }
     
     
     }
    
    
    
</style>
<script id="text" type="text/jsx" >

class Input extends React.Component {
  constructor(props) {
    super(props);
    this.val='';
    this.process= store.process.process.search;
  }

  send =()=>{
     console.log(this.process.input.main.prop.action); 
     search = document.getElementById('searchWord').value;    
     this.props.action();
    
  }

  render() {
      console.log(this.process.input);
    return (
      <div className='inputText'>
        <label>{this.process.input.main.prop.text.es}</label>
        <input type='text' id='searchWord' placeholder={this.process.input.main.prop.value}  />
        <button onClick ={()=>this.send()} >{this.process.input.main.prop.text.es}</button>
      </div>
    );
  }
}

</script>
