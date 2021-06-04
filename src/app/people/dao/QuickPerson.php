<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\people\dao;

use strings;
use sys;

abstract class QuickPerson {
	public static function get( $id) {
		$dao = new people;
		return ( $dao->getByID( $id));

	}

	public static function harvest( array $_qp) {
    /**
     * usage QuickPerson::harvest([
     *  'id' => '1',
     *  'name' => 'John Citizen',
     *  'mobile' => '0418 767676'
     *
     * ]);
     */
		$qp = (object)array_merge([
			'id' => 0,
			'name' => '',
			'mobile' => '',
			'email' => '',

		], $_qp);

		$debug = false;
		// $debug = true;

		if ( $qp->id) {
			$dao = new people;
			if ( $dto = $dao->getByID( $qp->id)) {

        if ( $debug) sys::logger( sprintf('<found (%d)> %s', $qp->id, __METHOD__));
				$a = [];
				if ( $qp->name && !$dto->name) $a['name'] = $qp->name;

				if ( $qp->mobile && strings::isMobilePhone( $qp->mobile)) {
					$mob = strings::cleanPhoneString( $qp->mobile);
					$myPhones = [];
					foreach ( [ $dto->telephone] as $_phone) {
						if ( $_phone = strings::cleanPhoneString( $_phone)) {
							$myPhones[] = $_phone;

						}

					}

					if ( !$dto->mobile && !( in_array( $mob, $myPhones))) {
						$a['mobile'] = $qp->mobile;

					}

				}

				if ( strings::isEmail( $qp->email)) {
					if ( !$dto->email && !( in_array( $qp->email, [ $dto->email2, $dto->email3]))) {
						$a['email'] = $qp->email;

					}

				}

				if ( $a) {
          if ( $debug) \sys::logger( sprintf('<%s> %s', json_encode( $a), __METHOD__));
          $dao->UpdateByID( $a, $dto->id);
          $dto = $dao->getByID( $dto->id);

				}

				return $dto;

			}
			else {
        if ( $debug) \sys::logger( sprintf('<not found %s> %s', $qp->id, __METHOD__));
				return self::find( $_qp);

			}

		}
		else {
      if ( $debug) \sys::logger( sprintf('<%s> %s', 'invalid id', __METHOD__));
			return self::find( $_qp);

		}

		return false;

	}

	public static function find( $a) {
    /**
     *
     * $a is an array
     * require name, tel, email
     *
     * usage QuickPerson::harvest([
     *  'id' => '1',
     *  'name' => 'John Citizen',
     *  'mobile' => '0418 767676'
     *
     * ]);
     */
		$debug = false;
    // $debug = true;

		$ret = [ 'id' => 0 ];

		$requirePhone = false;

		if ( !isset( $a['name'] )) {
			$ret['errorText'] = 'missing name';
      if ( $debug) \sys::logger( sprintf('<%s> %s', 'exit:: missing name', __METHOD__));
      return ( (object)$ret );

		}

		$ret['name'] = $a['name'];
		$ret['email'] = '';
		$ret['mobile'] = '';
		$ret['phone'] = '';
		$ret['isNew'] = false;

		$dao = new people;
		if ( isset( $a['email'])) {
			if ( strings::CheckEmailAddress( (string)$a['email'] )) {
				if ( $dto = $dao->getByEMAIL( (string)$a['email'])) {
          if ( $debug) \sys::logger( sprintf('<%s> found - email :%s', $a['email'], __METHOD__));

					/**
           * We may be able to harvest a phone
           */
          if ( isset( $a['phone'] )) {
            $theirPhones = [];
						foreach ( [ $dto->mobile] as $_p) {
              if ( $_p && strings::isPhone( $_p)) {
                $theirPhones[] = strings::cleanPhoneString( $_p);

							}

						}

						if ( $_phone = strings::isMobilePhone( $a['phone'])) {
              if ( !$dto->mobile && !in_array( $_phone, $theirPhones)) {
                if ( $debug) \sys::logger( sprintf('<%s> found - email harvested mobile :%s', $_phone, __METHOD__));
								$dao->UpdateByID(['mobile' => $_phone], $dto->id);

							}

						}
						elseif ( $_phone = strings::isPhone( $a['phone'])) {
              if ( !$dto->telephone && !in_array( $_phone, $theirPhones)) {
                if ( $debug) \sys::logger( sprintf('<%s> found - email harvested phone :%s', $_phone, __METHOD__));
								$dao->UpdateByID(['telephone' => $_phone], $dto->id);

							}

						}

					}

					return ( $dto);

				}

				$ret['email'] = (string)$a['email'];

			}
			else {
				$ret['errorText'] = 'invalid email : ' . (string)$a['email'];
				$requirePhone = true;

			}

		}
		else {
			$ret['errorText'] = 'missing email';
			$requirePhone = true;

		}

		foreach( ['phone', 'mobile', 'telephone_business'] as $fld) {
			if ( isset( $a[$fld] ) && trim( $a[$fld])) {
				if ( $dto = $dao->getByPHONE( (string)$a[$fld])) {
					return ( $dto);

				}

			}

		}

		$phone = false;
		if ( isset( $a['phone']) && trim( $a['phone'])) {
			$phone = $a['phone'];

		}
		elseif ( isset( $a['mobile']) && trim( $a['mobile'])) {
			$phone = $a['mobile'];

		}

		if ( !$phone && isset($a['telephone_business']) && !strings::isPhone( $a['telephone_business'])) {
			$ret['errorText'] = 'missing phone';
			if ( $requirePhone ) {
				return ( (object)$ret );

      }

		}

		if ( !( isset( $a['name'] ) && trim( $a['name']))) {
			$ret['errorText'] = 'not found and no name specified';
			return ( (object)$ret );

		}

    if ( $debug) sys::logger( sprintf('<%s> %s', 'still in game :: ' . ( isset( $ret['errorText']) ? $ret['errorText'] : 'no error' ), __METHOD__));

		$aI = [
			'name' => $ret['name'],
			'email' => $ret['email']

		];

		if ( strings::isPhone( $phone)) {
      strings::isMobilePhone( $phone) ?
        $aI['mobile'] = strings::cleanPhoneString( $phone) :
        $aI['telephone'] = strings::cleanPhoneString( $phone);

    }

		if ( isset( $a['mobile'] ) && strings::isMobilePhone( $a['mobile']) && $a['mobile'] != $phone) {
      $aI['mobile'] = strings::cleanPhoneString( (string)$a['mobile']);

    }

		if ( isset( $a['phone'] ) && strings::isPhone( $a['phone']) && $a['phone'] != $phone) {
      $aI['telephone'] = strings::cleanPhoneString( (string)$a['phone']);

    }

		if ( isset( $a['telephone_business'] ) && strings::isPhone( $a['telephone_business']) && $a['telephone_business'] != $phone) {
      $aI['telephone_business'] = strings::cleanPhoneString( (string)$a['telephone_business']);

    }

		if ( isset( $a['address_street'] ) && trim( $a['address_street'])) $aI['address_street'] = (string)$a['address_street'];
		if ( isset( $a['address_suburb'] ) && trim( $a['address_suburb'])) $aI['address_suburb'] = (string)$a['address_suburb'];
		if ( isset( $a['address_postcode'] ) && trim( $a['address_postcode'])) $aI['address_postcode'] = (string)$a['address_postcode'];
		if ( isset( $a['address_state'] ) && trim( $a['address_state'])) $aI['address_state'] = (string)$a['address_state'];

		$id = $dao->Insert( $aI);
		if ( $debug) \sys::logger( sprintf('<insert %s> %s', $id, __METHOD__));

		$dto = $dao->getByID( $id);
		$dto->isNew = 1;

		return ( $dto);

	}

}
