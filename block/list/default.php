<style>
    
    
 .list{
     float:left;
     width:90%;
     margin-left:5%;
     margin-right:5%;
     margin-top:5px;
     height:200px;
     }
 .item{
      
     color:#3374B2;
     float:left;
     width:100%;
     margin-top:2px;
     margin-bottom:2px; 
     height:30px;
     text-align: left;
     }
 
    @media (max-width: 12450px) {   
    
 .list{border:1px solid black;}
 .item{ color:#3374B2;}
 
     }
     
  @media (max-width: 800px) { 
       
  .list{border:1px solid black;}
 .item{ color:#3374B2;}
       
       } 
     
</style>
<script id="list" type="text/jsx" >

class List extends React.Component {
  constructor(props) {
    super(props);
   
    this.list= Object.values(props.list);
    
  }
  
  render() {
    return (
      <div className="list">
   
                    { 
                     this.list.map(item=>{
                           
                                return <a className="feet" key={item.prop.name+'_key'} href={item.prop.action} >{item.prop.text.es}</a>
                    })
                    }
         
      </div>
    );
  }
}

</script>