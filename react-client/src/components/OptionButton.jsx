
export default function OptionButton(props)
{
    return <button className={` w-full my-1 h-[50px] bg-gray-800 border-2 border-lime-300  rounded-md 
                            text-white text-[10px] sm:text-base hover:font-bold hover:border-yellow-300
                             hover:text-yellow-300 hover:bg-black ${props.value.slice(-4) === props.active ? "myactive" : ""}
                            `}
                    onClick={props.onOptionClick}>
                {props.value}
            </button>
}