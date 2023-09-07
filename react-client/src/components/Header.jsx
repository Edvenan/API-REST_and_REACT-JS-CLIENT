import { Link, useNavigate } from "react-router-dom";
import Swal from 'sweetalert2';
import logo from './../images/Logo_dices.png';
import { useRef, useContext } from "react";
import axios from "axios";
import AuthContext from "../services/AuthContext";
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const Header = () => {

    const navigate = useNavigate();
    const [isLoggedIn, setIsLoggedIn, user, setUser, roleRef, tokenRef, gamesList, setGamesList, winsRate, setWinsRate] = useContext(AuthContext);
    const emailRef = useRef();
    const passwordRef = useRef();
    const nameRef = useRef();
    const config = { headers: { Authorization: `Bearer ${tokenRef.current}` } };
    const bodyParameters = { key: "value" };
    
    function login_form() {

        Swal.fire({
            title: 'Login Form',
            html: `<input type="email" id="email" class="swal2-input" autocomplete='false' placeholder="Email" >
            <input type="password" id="password" class="swal2-input" autocomplete='false' placeholder="Password">`,
            confirmButtonText: 'Sign in',
            focusConfirm: false,
            preConfirm: () => {
              const email = Swal.getPopup().querySelector('#email').value
              const password = Swal.getPopup().querySelector('#password').value
              if (!email || !password ) {
                Swal.showValidationMessage(`Please enter email and password`)
              }
              return { email: email, password: password}
            }
          }).then((result) => {
            /* Swal.fire(`
              Email: ${result.value.email}
              Password: ${result.value.password}
            `.trim()) */
            if (result.isDismissed) return;
            emailRef.current = result.value.email;
            passwordRef.current = result.value.password;
            login();
          })
    }

    function login(){
        let email = emailRef.current;
        let password = passwordRef.current;
        const id = toast.loading("Login in progress...")
        axios.post("http://localhost:8000/api/v1/login", {email, password}).then(res => {
            tokenRef.current = res.data.token;
            setUser(res.data.user);
            roleRef.current = res.data.role;
            setIsLoggedIn(true);
            if (roleRef.current === 'player'){
                navigate("/player");
            }else if (roleRef.current === 'admin'){
                navigate("/admin");
            }
            toast.update(id, {render:"Login successful!", type:"success", isLoading: false, autoClose: 500 });

        }, (err) => {
            const msg = err.response.data.message;
            toast.update(id, {render: "Oops! " + msg?msg:"Something went wrong...", type:"error", isLoading: false, autoClose: 3000 });
          });
    }

    function register_form() {

        Swal.fire({
            title: 'Register Form',
            html: `
            <input type="text" id="name" class="swal2-input" placeholder="Name">
            <input type="email" id="email" class="swal2-input" placeholder="Email" required>
            <input type="password" id="password" class="swal2-input" minlength="6" required placeholder="Password">
            <input type="password" id="c_password" class="swal2-input" placeholder="Confirm password">
            `,
            confirmButtonText: 'Sign in',
            focusConfirm: true,
            preConfirm: () => {
              const name = Swal.getPopup().querySelector('#name').value
              const email = Swal.getPopup().querySelector('#email').value
              const password = Swal.getPopup().querySelector('#password').value
              const c_password = Swal.getPopup().querySelector('#c_password').value
              if (!email || !password || !c_password) {
                Swal.showValidationMessage(`Please enter email and password`)
              } else if (password !== c_password) {
                Swal.showValidationMessage(`Confirmed password does not match password`)
              }
              return { name: name, email: email, password: password, c_password: c_password}
            }
          }).then((result) => {
            if (result.isDismissed) return;
            nameRef.current = result.value.name;
            emailRef.current = result.value.email;
            passwordRef.current = result.value.password;
            register();
          })
          
    }

    function register(){
        let name = nameRef.current;
        let email = emailRef.current;
        let password = passwordRef.current;
        let c_password = passwordRef.current;
        const id = toast.loading("Registration in progress...")

        axios.post("http://localhost:8000/api/v1/players", {name, email, password, c_password}).then(res => {

            toast.update(id, {render:"Registration successful!. Please login.", type:"success", isLoading: false, autoClose: 2000});
            login_form();
        }, (err) => {

            const msg = err.response.data.error;

            // Get the first key in the object
            const firstKey = Object.keys(msg)[0];
            toast.update(id, {render: "Oops! " + msg[firstKey], type:"error", isLoading: false, autoClose: 3000 });
            register_form();
        });
    }

    function logout(){

        const id = toast.loading("Loging out...")
        axios.post("http://localhost:8000/api/v1/logout",[], config).then(res => {
            
            toast.update(id, {render:"Logout successful!", type:"success", isLoading: false, autoClose: 1000 });
            setIsLoggedIn(false);
            setUser(null);
            setGamesList([]);
            setWinsRate(0);
            navigate("/");
        }, (err) => {
            setIsLoggedIn(false);
            setUser(null);
            setGamesList([]);
            setWinsRate(0);
            navigate("/");
            toast.update(id, {render: "Oops! Invalid Token! Logging out anyway...", type:"error", isLoading: false, autoClose: 3000 });
        });
    }

    return (
        <header className="flex justify-between py-4 font-bold bg-black border-4 border-yellow-300  text-white items-center">
 
                {isLoggedIn?
                    <>
                        <div className="ml-4">
                                <img className="text-xs " src={logo} height="60px" width="60px" alt="logo"/>
                            <ToastContainer style={{ width: "auto" }}/>
                        </div>
                        <div className=" mt-2 text-yellow-300 font-Henny-Penny  text-3xl sm:text-5xl">
                            Rolling Dices!
                        </div>
                        <div className="mr-4 text-xs sm:text-sm cursor-pointer text-yellow-300 hover:text-[darkgoldenrod]"
                            onClick={logout}>Logout
                        </div>
                    </>
                    :
                    <>
                        <div className="ml-4">
                            <Link to="/">
                                <img className="text-xs cursor-pointer transition-transform transform hover:rotate-180" src={logo} height="60px" width="60px" alt="logo"/>
                            </Link>
                            <ToastContainer style={{ width: "auto" }}/>
                        </div>
                        <div className=" mt-2 text-yellow-300 font-Henny-Penny  text-3xl sm:text-5xl">
                            Rolling Dices!
                        </div>
                        <div className="flex ">
                            <div className="mr-4 text-xs cursor-pointer sm:text-sm text-yellow-300 hover:text-[darkgoldenrod]"
                                onClick={login_form}>Login
                            </div>
                            <div className="mr-4 text-xs cursor-pointer sm:text-sm text-yellow-300 hover:text-[darkgoldenrod]"
                            onClick={register_form}>Register
                            </div>
                        </div>
                    </>
                }      
        </header>
    )
}

export default Header;


