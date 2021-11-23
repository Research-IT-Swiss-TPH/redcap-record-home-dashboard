<?php
namespace STPH\recordHomeDashboard;

/**
 * Class Formatter
 *
 * A class for helping to format validation types
 * @author Ekin Tertemiz, Swiss Tropical and Public Health Institute
 * 
 */


class Formatter
{


    public static function renderDateFormat($value, $valtype) {
        
        $format = self::getDateFormatDisplay($valtype);
        $date = date_create($value);
        return date_format($date,$format);
    }

    /**
     *  Gets Date Format String from validation type
     *  Taken from MetaData::getDateFormatDisplay and adjusted to apply with PHP date_format()
     *  https://www.php.net/manual/en/datetime.format.php
     *  @param string $valtype
     *  @return string 
     *  @since 1.0.0
     * 
     */     
	private static function getDateFormatDisplay($valtype)
	{
		switch ($valtype)
		{
			case 'time':
				$dformat = "H:i";
				break;
			case 'date':
			case 'date_ymd':
				$dformat = "y-m-d";
				break;
			case 'date_mdy':
				$dformat = "m-d-y";
				break;
			case 'date_dmy':
				$dformat = "d-m-y";
				break;
			case 'datetime':
			case 'datetime_ymd':
				$dformat = "y-m-d H:i";
				break;
			case 'datetime_mdy':
				$dformat = "m-d-y H:i";
				break;
			case 'datetime_dmy':
				$dformat = "d-m-y H:i";
				break;
			case 'datetime_seconds':
			case 'datetime_seconds_ymd':
				$dformat = "y-m-d H:i:s";
				break;
			case 'datetime_seconds_mdy':
				$dformat = "m-d-y H:i:s";
				break;
			case 'datetime_seconds_dmy':
				$dformat = "d-m-y H:i:s";
				break;
			default:
				$dformat = '';
		}
		return $dformat;
	}  

}



