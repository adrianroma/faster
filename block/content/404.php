<style>
    
    .content{
        float:left;
        min-height:1700px;
        width:100%;
    }
    
    .paginator{
        float:left;
        margin-left:33.33%;
        width:33.33%;
        height:20px;
        text-align:center;
    }
    
    .pages{
       
	
	top: 25%;
        border:1px solid black;
	
      
    }
   
    .page{
        float:left;
        height: 20px;
        width: 20px;
       
        border:1px solid black;
        cursor:default;
    }
    
    @media (max-width: 12450px) { 
    
     .content{
        
        background-color:#FDFCFC;
       }
    
    }
    
    
     @media (max-width: 800px) { 
     
        .content{
        
        background-color:#FDFCFC;
       }
     
     
     }
    
    
    
</style>

<script id="Content" type="text/jsx" encoding = "utf-8" >

class Content extends React.Component {
  constructor(props) {
     super(props);
   this.state = {
            data:this.props.data,
            axion:this.props.axion,
            pages : function(){return 0 }
            }
 
 
 

    
  }
 
  render() {
    
    return (
      <div key='0' className='content'>
          
        <h1> 404 NOT FOUND </h1>
                   
       
      </div>
    );
  }
}

</script>