import logo from './../images/Logo_dices.png';

const Header = () => {
    return (

        <header className="flex py-4 font-bold justify-between bg-[#800080] bg-black border-4 border-yellow-300  text-white items-center">
                
                <img className="ml-4 text-xs cursor-pointer transition-transform transform hover:rotate-180" src={logo} height="60px" width="60px" alt="logo"/>
                
                <div className=" mt-2 text-yellow-300 font-Henny-Penny  text-3xl sm:text-5xl">
                    Rolling Dices!
                </div>
                <button className="mr-4 text-xs sm:text-sm text-yellow-300 hover:text-[darkgoldenrod]">
                    Logout
                </button>
                
        </header>
    )
}

export default Header;