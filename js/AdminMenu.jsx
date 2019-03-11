var imported = document.createElement('script');
imported.src = './js/react.development.js';
document.head.appendChild(imported);

var property = document.createElement('script');
property.src = './js/prop-types.min.js';
document.head.appendChild(property);

class AdminMenu extends React.Component {
 render(){
 return(<h1 name={this.props.attribute} >Hijo, {this.props.name}</h1>);
 }

}


AdminMenu.propTypes = {
  name: PropTypes.string,
  attribute: PropTypes.string
};

