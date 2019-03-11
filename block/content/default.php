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
   
   

   
 if(this.props.data[0]){
  this.limit = data[0]['limit'];
  this.pages = Math.ceil((this.props.data[0]['n'])/(this.props.data[0]['limit'])); 
  this.counter = 0; 
  this.goTo = this.goTo.bind(this);
  this.goToService = this.goToService.bind(this);
  
 }
 
  this.paginator();
  
    
  }
  
 goTo =(number)=>{

          page = number;
          this.props.action();
       

      
  }
  
  goToService = (uri,code)=> {
     
      
    
      let URI = this.getCleanedString(uri,code);
  
      window.location = "/"+code+"/"+URI;
      
      
  }
  
  
  getCleanedString = (cadena)=>{
      let _cadena = cadena;
   // Definimos los caracteres que queremos eliminar
   let specialChars = "!@#$^&%*()+=-[]\/{}|:<>?,.";
   
   
   // Los eliminamos todos
   for (let i = 0; i < specialChars.length; i++) {
        _cadena= _cadena.replace(new RegExp("\\" + specialChars[i], 'gi'), '');
   }   

   // Lo queremos devolver limpio en minusculas
   _cadena = _cadena.toLowerCase();

   // Quitamos espacios y los sustituimos por _ porque nos gusta mas asi
   _cadena = _cadena.replace(/ /g,"_");

   // Quitamos acentos y "ñ". Fijate en que va sin comillas el primer parametro
   _cadena = _cadena.replace(/á/gi,"a");
   _cadena = _cadena.replace(/é/gi,"e");
   _cadena = _cadena.replace(/í/gi,"i");
   _cadena = _cadena.replace(/ó/gi,"o");
   _cadena = _cadena.replace(/ú/gi,"u");
   _cadena = _cadena.replace(/ñ/gi,"n");
   return _cadena;
}
  
  paginator(){
             
              let pagination = [];
              let pages = Math.ceil((this.props.data[0]['n'])/(this.props.data[0]['limit'])); 
              for(this.counter=1;this.counter<pages;this.counter++){
                   let number = this.counter;
                   let item = <a className="page" key={this.counter+'-page'} id={'page:'+number} onClick={ ()=>this.goTo(number)} >{this.counter}</a>
                   pagination.push(item)
              }
    
    
     if(pagination.length===0){
   
     return <div></div>  
     }else{
    
      return <div className="paginator" ><div className="pages" >{pagination}</div></div>
     }   
      
  }
  
  master(){
      if(this.props.axion==='true'){
      return <a></a>;
      }else{
      return <a>real</a>    
      }
  }

    action(){
      
     this.props.action();
     //this.props.action();
  
     }

  render() {
    
    return (
      <div key='0' className='content'>
          
          <div>
          
          <Input action={()=>this.action()} />
          </div>
           { this.props.data.map(item=>{
                  
                   return <span id={item.code}  key={item.code+'-a'} onClick={()=>this.goToService(item.career,item.code)} ><p>{item.career}</p></span>
           })}
   
           
           
          {this.master()}
                  
              {this.paginator()}    
           
                   
       
      </div>
    );
  }
}

</script>