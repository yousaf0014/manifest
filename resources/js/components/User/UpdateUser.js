import React, {Component} from 'react';
import axios from 'axios';
import { Link } from 'react-router';
import MyGlobleSetting from './MyGlobleSetting';
class UpdateUser extends Component {
  constructor(props) {
    super(props);
    this.state = {firstname: '', lastaname: '',email:'',password:''};
    this.handleChangeFirstName = this.handleChangeFirstName.bind(this);
    this.handleChangeLastName = this.handleChangeLastName.bind(this);
    this.handleChangeEmail = this.handleChangeEmail.bind(this);
    this.handleChangePassword = this.handleChangePassword.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  componentDidMount(){
    axios.get(MyGlobleSetting.url + `/api/edit/${this.props.params.id}`)
    .then(response => {
      let data = response.data.success;
      this.setState({ firstname: data.first_name, lastaname: data.last_name,email:data.email});
    })
    .catch(function (error) {
      console.log(error);
    })
  }
  handleChangeFirstName(e){
    this.setState({
      firstname: e.target.value
    })
  }
  handleChangeLastName(e){
    this.setState({
      lastaname: e.target.value
    })
  }
  handleChangeEmail(e){
    this.setState({
      email: e.target.value
    })
  }
  handleChangePassword(e){
    this.setState({
      password: e.target.value
    })
  }
  
  handleSubmit(event) {
    event.preventDefault();
    const products = {
      first_name: this.state.firstname,
      last_name: this.state.lastaname,
      email: this.state.email,
      password: this.state.password,
      mobile_key:'yYgIvy8fK127X9GpSlepkuJmy7c7f7rB7p7Tn08lGzo0'
    }

    let uri = MyGlobleSetting.url + '/api/update/'+this.props.params.id;
    axios.patch(uri, products).then((response) => {
      this.props.history.push('/display-item');
    });
  }

  render(){
    return (
      <div>
        <h1>Update Product</h1>
        <div className="row">
          <div className="col-md-10"></div>
          <div className="col-md-2">
            <Link to="/display-item" className="btn btn-success">Return to Users</Link>
          </div>
        </div>

        <form onSubmit={this.handleSubmit}>
          <div className="row">
            <div className="col-md-6">
              <div className="form-group">
                <label>First Name:</label>
                <input type="text" className="form-control" value={this.state.firstname} onChange={this.handleChangeFirstName} />
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-md-6">
              <div className="form-group">
                <label>Last Name:</label>
                <input type="text" className="form-control" value={this.state.lastaname||''} onChange={this.handleChangeLastName} />
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-md-6">
              <div className="form-group">
                <label>Email:</label>
                <input type="text" className="form-control" value={this.state.email||''} onChange={this.handleChangeEmail} />
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-md-6">
              <div className="form-group">
                <label>Password:</label>
                <input type="password" className="form-control" value={this.state.password||''} onChange={this.handleChangePassword} />
              </div>
            </div>
          </div>
            
          <div className="form-group">
              <button className="btn btn-primary">Update</button>
          </div>
        </form>
    </div>
    )
  }
}
export default UpdateUser;