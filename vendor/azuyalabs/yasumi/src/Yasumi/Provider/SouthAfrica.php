<?php

/**
 * This file is part of the Yasumi package.
 *
 * Copyright (c) 2015 - 2019 AzuyaLabs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Sacha Telgenhof <me@sachatelgenhof.com>
 */

namespace Yasumi\Provider;

use DateInterval;
use DateTime;
use DateTimeZone;
use Yasumi\Holiday;

/**
 * Provider for all holidays in South Africa.
 *
 * Note: South Africa has 12 public holidays as determined by the Public Holidays Act (Act No 36 of 1994). The Act
 * determines whenever any public holiday falls on a Sunday, the Monday following on it shall be a public holiday.
 * Yasumi currently implements all South African holidays based on this act.
 */
class SouthAfrica extends AbstractProvider
{
    use CommonHolidays, ChristianHolidays;

    /**
     * Code to identify this Holiday Provider. Typically this is the ISO3166 code corresponding to the respective
     * country or sub-region.
     */
    const ID = 'ZA';

    /**
     * Initialize holidays for South Africa.
     *
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     * @throws \Exception
     */
    public function initialize()
    {
        $this->timezone = 'Africa/Johannesburg';

        if ($this->year < 1994) {
            return;
        }

        // Add common holidays
        $this->addHoliday($this->newYearsDay($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->internationalWorkersDay($this->year, $this->timezone, $this->locale));

        // Add common Christian holidays (common in SouthAfrica)
        $this->addHoliday($this->goodFriday($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->christmasDay($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->secondChristmasDay($this->year, $this->timezone, $this->locale));

        // Calculate other holidays
        $this->calculateHumanRightsDay();
        $this->calculateFamilyDay();
        $this->calculateFreedomDay();
        $this->calculateYouthDay();
        $this->calculate2016MunicipalElectionsDay();
        $this->calculateNationalWomensDay();
        $this->calculateHeritageDay();
        $this->calculateDayOfReconciliation();
        $this->calculateSubstituteDayOfGoodwill();

        // Determine whether any of the holidays is substituted on another day
        $this->calculateSubstituteHolidays();
    }

    /**
     * Human Rights Day.
     *
     * The Bill of Rights contained in the Constitution is the cornerstone of democracy in South Africa.
     * The Constitution provides for the establishment of the South African Human Rights Commission (SAHRC). The aim of
     * the Commission is to promote respect for human rights, promote the protection, development and attainment of
     * human rights, and to monitor and assess the observance of human rights in SA. The SAHRC was launched on 21 March
     * 1996, 35 years after the fateful events of 21 March 1960 when demonstrators in Sharpeville were gunned down by
     * police.
     *
     * @link http://www.gov.za/about-sa/public-holidays#21march
     *
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     */
    public function calculateHumanRightsDay()
    {
        $this->addHoliday(new Holiday(
            'humanRightsDay',
            ['en_ZA' => 'Human Rights Day'],
            new DateTime($this->year . '-3-21', new DateTimeZone($this->timezone)),
            $this->locale
        ));
    }

    /**
     * Family Day.
     *
     * Family Day in South Africa takes place on the Monday following Easter Sunday.
     *
     * @link http://www.gov.za/sites/www.gov.za/files/Act36of1994.pdf
     *
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     * @throws \Exception
     */
    public function calculateFamilyDay()
    {
        $this->addHoliday(new Holiday(
            'familyDay',
            ['en_ZA' => 'Family Day'],
            $this->calculateEaster($this->year, $this->timezone)->add(new DateInterval('P1D')),
            $this->locale
        ));
    }

    /**
     * Freedom Day.
     *
     * Freedom Day commemorates the first democratic elections held in South Africa on 27 April 1994.
     *
     * @link http://www.gov.za/sites/www.gov.za/files/Act36of1994.pdf
     * @link http://www.gov.za/freedom-day-2014
     *
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     */
    public function calculateFreedomDay()
    {
        $this->addHoliday(new Holiday(
            'freedomDay',
            ['en_ZA' => 'Freedom Day'],
            new DateTime($this->year . '-4-27', new DateTimeZone($this->timezone)),
            $this->locale
        ));
    }

    /**
     * Youth Day.
     *
     * In 1975 protests started in African schools after a directive from the then Bantu Education Department that
     * Afrikaans had to be used on an equal basis with English as a language of instruction in secondary schools.
     * On 16 June 1976 more than 20 000 pupils from Soweto began a protest march. In the wake of clashes with the
     * police, and the violence that ensued during the next few weeks, approximately 700 hundred people, many of them
     * youths, were killed and property destroyed. Youth Day, previously known as Soweto Day, commemorates these events.
     *
     * @link http://www.gov.za/sites/www.gov.za/files/Act36of1994.pdf
     * @link http://www.gov.za/youth-day-2014
     *
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     */
    public function calculateYouthDay()
    {
        $this->addHoliday(new Holiday(
            'youthDay',
            ['en_ZA' => 'Youth Day'],
            new DateTime($this->year . '-6-16', new DateTimeZone($this->timezone)),
            $this->locale
        ));
    }

    /**
     * 2016 Municipal Elections Day.
     *
     * President Jacob Zuma has declared 3 August 2016, the date of the 2016 Municipal Elections, a public holiday. The
     * purpose is to enable all South Africans who are eligible to vote to exercise their right on 3 August 2016.
     *
     * @link http://www.gov.za/speeches/president-jacob-zuma-declares-3-august-2016-public-holiday-24-jun-2016-0000
     *
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     */
    public function calculate2016MunicipalElectionsDay()
    {
        if ($this->year !== 2016) {
            return;
        }

        $this->addHoliday(new Holiday(
            '2016MunicipalElectionsDay',
            ['en_ZA' => '2016 Municipal Elections Day'],
            new DateTime('2016-8-3', new DateTimeZone($this->timezone)),
            $this->locale
        ));
    }

    /**
     * National Women's Day.
     *
     * This day commemorates 9 August 1956 when women participated in a national march to petition against pass laws
     * (legislation that required African persons to carry a document on them to 'prove' that they were allowed to enter
     * a 'white area').
     *
     * @link http://www.gov.za/about-sa/public-holidays#women
     * @link http://www.gov.za/womens-day
     *
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     */
    public function calculateNationalWomensDay()
    {
        $this->addHoliday(new Holiday(
            'nationalWomensDay',
            ['en_ZA' => 'National Women\'s Day'],
            new DateTime($this->year . '-8-9', new DateTimeZone($this->timezone)),
            $this->locale
        ));
    }

    /**
     * Heritage Day.
     *
     * Heritage Day on 24 September recognises and celebrates the cultural wealth of the South African nation. South
     * Africans celebrate the day by remembering the cultural heritage of the many cultures that make up the population
     * of South Africa.
     *
     * @link http://www.gov.za/sites/www.gov.za/files/Act36of1994.pdf
     * @link http://www.gov.za/heritage-day-2014
     *
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     */
    public function calculateHeritageDay()
    {
        $this->addHoliday(new Holiday(
            'heritageDay',
            ['en_ZA' => 'Heritage Day'],
            new DateTime($this->year . '-9-24', new DateTimeZone($this->timezone)),
            $this->locale
        ));
    }

    /**
     * Day of Reconciliation.
     *
     * In apartheid South Africa 16 December was known as Day of the Vow, as the Voortrekkers in preparation for the
     * battle on 16 December against the Zulus took a Vow before God that they would build a church and that they and
     * their descendants would observe the day as a day of thanksgiving should they be granted victory. With the advent
     * of democracy in South Africa 16 December retained its status as a public holiday, however, this time with the
     * purpose of fostering reconciliation and national unity.
     *
     * @link http://www.gov.za/sites/www.gov.za/files/Act36of1994.pdf
     * @link http://www.gov.za/day-reconciliation-2014
     *
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     */
    public function calculateDayOfReconciliation()
    {
        $this->addHoliday(new Holiday(
            'reconciliationDay',
            ['en_ZA' => 'Day of Reconciliation'],
            new DateTime($this->year . '-12-16', new DateTimeZone($this->timezone)),
            $this->locale
        ));
    }

    /**
     * Substitute Day of Goodwill 2016.
     *
     * In 2016 Christmas Day is observed on the next day as it falls on a Sunday in 2016. Since it coincides with the
     * second day of Christmas (Day of Goodwill), a substitute day is given for December 27th.
     *
     * Note: Not entirely sure if this is a common rule as the Public Holidays Act doesn't mention such specific
     * situation.
     *
     * @link http://www.gov.za/sites/www.gov.za/files/Act36of1994.pdf
     *
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     */
    public function calculateSubstituteDayOfGoodwill()
    {
        if ($this->year !== 2016) {
            return;
        }

        $this->addHoliday(new Holiday(
            'substituteDayOfGoodwill',
            ['en_ZA' => 'Day of Goodwill observed'],
            new DateTime('2016-12-27', new DateTimeZone($this->timezone)),
            $this->locale
        ));
    }

    /**
     * Calculate substitute holidays.
     *
     * The Public Holidays Act (Act No 36 of 1994) determines whenever any public holiday falls on a Sunday, the Monday
     * following on it shall be a public holiday.
     *
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     * @throws \Exception
     */
    private function calculateSubstituteHolidays()
    {
        $datesIterator = $this->getIterator();

        // Loop through all defined holidays
        while ($datesIterator->valid()) {

            // Exclude Good Friday, Family Day, 2016 Municipal Elections Day as these don't fall in the weekend
            if (in_array(
                $datesIterator->current()->shortName,
                ['goodFriday', 'familyDay', '2016MunicipalElectionsDay'],
                true
            )) {
                $datesIterator->next();
            }

            // Substitute holiday is on a Monday in case the holiday falls on a Sunday
            if (0 === (int)$datesIterator->current()->format('w')) {
                $substituteHoliday = clone $datesIterator->current();
                $substituteHoliday->add(new DateInterval('P1D'));

                $this->addHoliday(new Holiday('substituteHoliday:' . $substituteHoliday->shortName, [
                    'en_ZA' => $substituteHoliday->getName() . ' observed',
                ], $substituteHoliday, $this->locale));
            }

            $datesIterator->next();
        }
    }
}
