import RollDice from "./rolldice/RollDice";
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import slide1 from './../../images/slide4.png';

library.add(fas)

const PlayGame = () => {
    return (

        <div className="flex flex-grow border-4 border-yellow-300 bg-black mb-0.5 px-2  
                        justify-center bg-no-repeat bg-center bg-cover" style={{ 'backgroundImage': `url(${slide1})` }} >
            <div className="flex flex-col items-center w-full">    
                <h1 className="my-2 font-bold text-xl sm:text-2xl text-center text-white animate-pulse ">
                    Play a new Game!
                </h1>
                <RollDice />
            </div>
        </div>
    )
}

export default PlayGame;