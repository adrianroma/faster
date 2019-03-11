<style>
    
    .grid{
        
         float:left;
         width:100%;
         
       }
    
       
       .clock{
           
         position:fixed;
         top:10px;
         left:10px;
           
       } 
    
    
    
    @media (max-width: 12450px) { 
    
     .grid{
        
        background-color: silver;
       }
    
    }
    
    
     @media (max-width: 800px) { 
     
        .grid{
        
        background-color: blue;
       }
     
     
     }
    
    
    
</style>
<script id="grid" type="text/jsx" >
class Grid extends React.Component {
  constructor(props) {
    super(props);
    data = [{n:0,limit:0}];
    this.action = this.action.bind(this);
    
    this.state = {
                  input:input,
                  data:data,
                  store:store,
                  user:user,
                  axion:'true',
                  service:function(){ return service }
                 } 
  }
  
 
  
  action(){
      
   axios.post('/request',
   {0:{action: "service"}}
   )
   .then(response =>{ console.log(response.data)})  

   //.then(response =>{ this.setState({data:response.data}) })  
   
   //this.setState({axion:'false'});

   
  }
  
  render() {
    return (
      <div className='grid'>
        <Header  data={this.state.data} />
        <span className='clock'></span>
        <Content action={this.action} data={this.state.data} service={this.state.service()} axion={this.state.axion} />            
        <Footer store={this.state.store} />            
     </div>
    );
  }
}


ReactDOM.render(<Grid/>,document.getElementById('root'));

</script>

<div id ='root'></div>
</body>
</html>