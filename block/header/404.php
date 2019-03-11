<style>
    
    .header{
        
        float:left;
        height:300px;
        width:100%;
        border-bottom:1px solid silver;
        
       }
    
     
   
    
    @media (max-width: 12450px) { 
    
     .header{
        
        background-color:#FDFCFC;
      
       }
    
    }
    
    
     @media (max-width: 800px) { 
     
        .header{
        
        background-color:#FDFCFC;
       }
     
     
     }
    
    
    
</style>
<script id="header" type="text/jsx" >

class Header extends React.Component {
  constructor(props) {
    super(props);
  
    this.state = {
                  message:'Service',
                  data:this.props.data
                 }

    
  }
  
  action(){
      
     
     this.setState({message:'hey'});
     this.props.action();
  
     }

  render() {
    return (
      <div className='header'>
        
  
      </div>
    );
  }
}

</script>
